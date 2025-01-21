<?php
require_once(__DIR__ . '/db.php');

class Course
{
    protected $db;
    protected $id;
    protected $instructorId;
    protected $title;
    protected $description;
    protected $price;
    protected $categoryId;
    protected $thumbnail;
    protected $content;
    protected $createdDate;
    protected $studentCount;
    protected $Duration;
    protected $Difficulty;

    public function __construct()
    {
        $this->db = new Database();
    }

    public static function browseCourses($db, $limit, $offset)
    {
        $connection = $db->getConnection();
        $query = "SELECT 
                c.id AS course_id, 
                c.title, 
                c.description, 
                c.price, 
                c.thumbnail,
                u.username AS instructor_name
              FROM courses c
              LEFT JOIN users u ON c.instructorId = u.id
              LIMIT $limit OFFSET $offset";
        $result = $connection->query($query);

        $courses = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $courses[] = $row;
            }
        }
        return $courses;
    }


    public static function countCourses($db)
    {
        $connection = $db->getConnection();
        $query = "SELECT COUNT(*) AS total FROM courses";
        $result = $connection->query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc()['total'];
        }
        return 0;
    }

    public function getCourseById($id)
    {
        $connection = $this->db->getConnection();
        $id = $connection->real_escape_string($id);

        $query = "SELECT c.*, 
                     cat.name AS category_name, 
                     COUNT(e.studentId) AS student_count
              FROM courses c
              LEFT JOIN categories cat ON c.categoryId = cat.id
              LEFT JOIN enrollment e ON e.courseId = c.id
              WHERE c.id = '$id'
              GROUP BY c.id, c.title, c.description, c.price, c.categoryId, 
                       c.thumbnail, c.content, c.videoUrl, c.createdDate, 
                       c.instructorId, c.Difficulty, c.Duration, cat.name";
        $result = $connection->query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function getInstructorInfo($instructorId)
    {
        $connection = $this->db->getConnection();

        $query = "SELECT u.username, u.avatarImg, u.bio, u.poste, 
                         COUNT(DISTINCT e.studentId) AS total_students,
                         COUNT(DISTINCT c.id) AS total_courses
                  FROM users u
                  LEFT JOIN courses c ON u.id = c.instructorId
                  LEFT JOIN enrollment e ON c.id = e.courseId
                  WHERE u.id = ?
                  GROUP BY u.id";

        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $instructorId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }

    public function isUserEnrolled($studentId, $courseId)
    {
        $connection = $this->db->getConnection();
        $query = "SELECT COUNT(*) as count FROM enrollment WHERE studentId = ? AND courseId = ?";
        if ($stmt = $connection->prepare($query)) {
            $stmt->bind_param("ii", $studentId, $courseId);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $stmt->close();
            return $data['count'] > 0;
        } else {
            throw new Exception("Failed to prepare the query: " . $connection->error);
        }
    }

    public function search($searchTerm)
    {
        $connection = $this->db->getConnection();
        $searchTerm = $connection->real_escape_string($searchTerm);

        $query = "SELECT 
              c.id AS course_id, 
              c.title, 
              c.description, 
              c.price, 
              c.thumbnail,
              u.username AS instructor_name,
              cat.name AS category_name
          FROM courses c
          LEFT JOIN users u ON c.instructorId = u.id
          LEFT JOIN categories cat ON c.categoryId = cat.id
          WHERE c.title LIKE ? 
             OR c.description LIKE ? 
             OR u.username LIKE ?
             OR cat.name LIKE ?
          LIMIT 20";

        $stmt = $connection->prepare($query);
        $searchWildcard = '%' . $searchTerm . '%';
        $stmt->bind_param("ssss", $searchWildcard, $searchWildcard, $searchWildcard, $searchWildcard);

        $stmt->execute();
        $result = $stmt->get_result();

        $courses = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $courses[] = $row;
            }
        }
        $stmt->close();
        return $courses;
    }


    public function addCourse($title, $description, $price, $difficulty, $duration, $thumbnail, $categoryId, $tags, $contentType, $contentFile)
    {
        $query = "INSERT INTO courses (title, `description`, price, categoryId, thumbnail, document, videoUrl, Difficulty, Duration, instructorId, `type`, `status`) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);

        $thumbnailFileName = $thumbnail ? basename($thumbnail) : null;
        $document = ($contentType === 'document' && $contentFile) ? basename($contentFile) : null;
        $videoUrl = ($contentType === 'video' && $contentFile) ? basename($contentFile) : null;

        $instructorId = $_SESSION['user_id'];
        $status = 'Published';

        $stmt->bind_param(
            "ssdissssisss",
            $title,
            $description,
            $price,
            $categoryId,
            $thumbnailFileName,
            $document,
            $videoUrl,
            $difficulty,
            $duration,
            $instructorId,
            $contentType,
            $status
        );

        if ($stmt->execute()) {
            $courseId = $this->db->insert_id;

            if (!$courseId) {
                throw new Exception("Failed to retrieve the last inserted course ID.");
            }

            if (!empty($tags)) {
                if (is_string($tags)) {
                    $tags = explode(',', $tags);
                }

                $tagQuery = "INSERT INTO coursetag (courseId, tagId) VALUES (?, ?)";
                $tagStmt = $this->db->prepare($tagQuery);

                foreach ($tags as $tag) {
                    $tagStmt->bind_param("ii", $courseId, $tag);
                    if (!$tagStmt->execute()) {
                        echo "Failed to insert tag ID $tag for course ID $courseId: " . $tagStmt->error . "<br>";
                    }
                }
            }

            return true;
        } else {
            throw new Exception("Course insertion failed: " . $stmt->error);
        }
    }

    public function deleteCourse($courseId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM courses WHERE id = ?");
            $stmt->bind_param("i", $courseId); 
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            throw new Exception("Error deleting course: " . $e->getMessage());
        }
    }

    public function updateCompletionRate($courseId, $studentId, $completionRate) {
        $query = "UPDATE enrollment 
                  SET completionRate = ? 
                  WHERE courseId = ? AND studentId = ?";
        $stmt = $this->db->prepare($query);

        if ($stmt) {
            $stmt->bind_param("sii", $completionRate, $courseId, $studentId);
            return $stmt->execute();
        } else {
            return false; 
        }
    }

    public function getCourseTags($courseId) {
        $stmt = $this->db->prepare('SELECT t.id, t.name FROM Tags t 
                                    JOIN coursetag ct ON ct.tagId = t.id 
                                    WHERE ct.courseId = ?');
        if ($stmt) {
            $stmt->bind_param('i', $courseId); 
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public function editCourse($id, $title, $description, $price, $difficulty, $duration, $thumbnail, $categoryId, $tags, $contentType, $contentFile) {
        $thumbnailFileName = NULL;
        $documentFileName = NULL;
        $videoUrl = NULL;
    
        if ($thumbnail) {
            $thumbnailFileName = basename($thumbnail); 
        }
    
        if ($contentFile) {
            if ($contentType === 'document') {
                $documentFileName = basename($contentFile);
            } elseif ($contentType === 'video') {
                $videoUrl = basename($contentFile);
            }
        }
    
        $query = "UPDATE courses 
                  SET title = ?, 
                      description = ?, 
                      price = ?, 
                      categoryId = ?, 
                      Difficulty = ?, 
                      Duration = ?, 
                      `type` = ?, 
                      thumbnail = CASE WHEN ? IS NOT NULL THEN ? ELSE thumbnail END, 
                      document = CASE WHEN ? = 'document' AND ? IS NOT NULL THEN ? ELSE document END, 
                      videoUrl = CASE WHEN ? = 'video' AND ? IS NOT NULL THEN ? ELSE videoUrl END
                  WHERE id = ?";
    
        $stmt = $this->db->prepare($query);
    
        $stmt->bind_param(
            "ssdissssssssssss", 
            $title, 
            $description, 
            $price, 
            $categoryId, 
            $difficulty, 
            $duration, 
            $contentType, 
            $thumbnailFileName, 
            $thumbnailFileName, 
            $contentType, 
            $documentFileName, 
            $documentFileName, 
            $contentType,
            $videoUrl,
            $videoUrl,
            $id
        );
    
        if ($stmt->execute()) {
            $deleteTagsQuery = "DELETE FROM coursetag WHERE courseId = ?";
            $deleteStmt = $this->db->prepare($deleteTagsQuery);
            $deleteStmt->bind_param("i", $id);
            $deleteStmt->execute();
    
            if (!empty($tags)) {
                if (is_string($tags)) {
                    $tags = explode(',', $tags);
                }
    
                $tagQuery = "INSERT INTO coursetag (courseId, tagId) VALUES (?, ?)";
                $tagStmt = $this->db->prepare($tagQuery);
    
                foreach ($tags as $tag) {
                    $tagStmt->bind_param("ii", $id, $tag);
                    if (!$tagStmt->execute()) {
                        echo "Failed to insert tag ID $tag for course ID $id: " . $tagStmt->error . "<br>";
                    }
                }
            }
    
            return true;
        } else {
            throw new Exception("Failed to update course: " . $stmt->error);
        }
    }

    public function __destruct()
    {
        $this->db->closeConnection();
    }
}
?>