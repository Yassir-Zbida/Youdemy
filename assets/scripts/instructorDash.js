document.addEventListener("DOMContentLoaded", function () {
  const toggleHeader = document.getElementById("toggleFormHeader");
  const courseForm = document.getElementById("courseForm");
  const toggleIcon = document.getElementById("toggleIcon");
  const newCourseBtn = document.getElementById("newCourseBtn");

  courseForm.classList.add("hidden");

  toggleHeader.addEventListener("click", function () {
    courseForm.classList.toggle("hidden");
    toggleIcon.style.transform = courseForm.classList.contains("hidden")
      ? "rotate(0deg)"
      : "rotate(180deg)";
  });

  newCourseBtn.addEventListener("click", function () {
    courseForm.classList.remove("hidden");
    toggleIcon.style.transform = "rotate(180deg)";
    document.getElementById("add").scrollIntoView({ behavior: "smooth" });
  });

  if (window.location.hash === "#add") {
    courseForm.classList.remove("hidden");
    toggleIcon.style.transform = "rotate(180deg)";
    document.getElementById("add").scrollIntoView({ behavior: "smooth" });
  }
});

const selectElement = document.getElementById("content-type");
const videoUploadDiv = document.getElementById("video-upload");
const documentUploadDiv = document.getElementById("document-upload");

selectElement.addEventListener("change", (event) => {
  const selectedValue = event.target.value;
  console.log("Selected value:", selectedValue);

  videoUploadDiv.classList.add("hidden");
  documentUploadDiv.classList.add("hidden");

  if (selectedValue === "video") {
    videoUploadDiv.classList.remove("hidden");
  } else if (selectedValue === "document") {
    documentUploadDiv.classList.remove("hidden");
  }
});

const fileInput = document.getElementById("file-upload-video");
const fileNameDisplay = document.getElementById("file-name");

fileInput.addEventListener("change", (event) => {
  const file = event.target.files[0];
  if (file) {
    fileNameDisplay.textContent = `Selected Video: ${file.name}`;
  } else {
    fileNameDisplay.textContent = "";
  }
});

const documentInput = document.getElementById("file-upload-document");
const documentFileNameDisplay = document.getElementById("document-file-name");

documentInput.addEventListener("change", (event) => {
  const file = event.target.files[0];
  if (file) {
    documentFileNameDisplay.textContent = `Selected file: ${file.name}`;
  } else {
    documentFileNameDisplay.textContent = "";
  }
});

const thumbnailInput = document.getElementById("file-upload-thumbnail");
const thumbnailFileNameDisplay = document.getElementById("thumbnail-file-name");

thumbnailInput.addEventListener("change", (event) => {
  const file = event.target.files[0];
  if (file) {
    thumbnailFileNameDisplay.textContent = `Selected file: ${file.name}`;
  } else {
    thumbnailFileNameDisplay.textContent = "";
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const availableTagsContainer = document.getElementById("available-tags");
  const selectedTagsContainer = document.getElementById("selected-tags");
  const selectedTagsHiddenInput = document.getElementById(
    "selected-tags-hidden"
  );
  const selectedTags = new Set();

  function updateHiddenInput() {
    const selectedTagsArray = Array.from(selectedTags);
    selectedTagsHiddenInput.value = selectedTagsArray.join(",");
    console.log("Hidden input updated:", selectedTagsHiddenInput.value);
  }

  function handleTagClick(event) {
    const tagItem = event.target.closest(".tag-item");
    if (!tagItem) return;

    const tagId = tagItem.getAttribute("data-tag-id");
    const container = tagItem.closest("#available-tags, #selected-tags");

    const clonedTag = tagItem.cloneNode(true);

    if (container === availableTagsContainer) {
      clonedTag.classList.remove("hover:bg-yellow-400");
      clonedTag.classList.add("bg-yellow-400", "text-white");

      const removeIcon = document.createElement("span");
      removeIcon.textContent = "×";
      removeIcon.classList.add(
        "remove-icon",
        "ml-2",
        "text-white",
        "cursor-pointer"
      );
      clonedTag.appendChild(removeIcon);

      selectedTagsContainer.appendChild(clonedTag);
      selectedTags.add(tagId);
    } else {
      clonedTag.classList.remove("bg-yellow-400", "text-white");
      clonedTag.classList.add("hover:bg-yellow-400");

      const removeIcon = clonedTag.querySelector(".remove-icon");
      if (removeIcon) removeIcon.remove();

      availableTagsContainer.appendChild(clonedTag);
      selectedTags.delete(tagId);
    }

    tagItem.remove();
    updateHiddenInput();
  }

  availableTagsContainer.addEventListener("click", handleTagClick);
  selectedTagsContainer.addEventListener("click", handleTagClick);
});

document.querySelector("form").addEventListener("submit", (event) => {
  updateHiddenInput();
});

document.querySelector("form").addEventListener("submit", function (e) {
  e.preventDefault();

  const hours = parseInt(
    document.getElementById("duration_hours").value || "0"
  );
  const minutes = parseInt(
    document.getElementById("duration_minutes").value || "0"
  );

  let durationString = "";

  if (hours > 0) {
    durationString += `${hours} hour${hours !== 1 ? "s" : ""}`;
  }

  if (minutes > 0) {
    if (hours > 0) durationString += " ";
    durationString += `${minutes} min${minutes !== 1 ? "s" : ""}`;
  }

  if (durationString === "") {
    durationString = "0 mins";
  }

  const hiddenInput = document.createElement("input");
  hiddenInput.type = "hidden";
  hiddenInput.name = "duration";
  hiddenInput.value = durationString;
  this.appendChild(hiddenInput);

  this.submit();
});


function confirmDelete(courseId) {
  if (confirm("Are you sure you want to delete this course?")) {
      window.location.href = 'deleteCourse.php?id=' + courseId;
  }
}