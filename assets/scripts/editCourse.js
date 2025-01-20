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
    const selectedTagsHiddenInput = document.getElementById("selected-tags-hidden");
    const selectedTags = new Set();
  
    const selectedTagItems = document.querySelectorAll("#available-tags .tag-item[data-selected='selected']");
    selectedTagItems.forEach(item => {
      const tagId = item.getAttribute("data-tag-id");
      selectedTags.add(tagId);
      const clonedItem = item.cloneNode(true);
      clonedItem.classList.remove("hover:bg-yellow-400");
      clonedItem.classList.add("bg-yellow-400", "text-white");
      addRemoveIcon(clonedItem);
      selectedTagsContainer.appendChild(clonedItem);
      item.remove(); 
    });
  
    function updateHiddenInput() {
      const selectedTagsArray = Array.from(selectedTags);
      selectedTagsHiddenInput.value = selectedTagsArray.join(",");
      console.log("Hidden input updated:", selectedTagsHiddenInput.value);
    }
  
    function addRemoveIcon(tagItem) {
      if (!tagItem.querySelector(".remove-icon")) {
        const removeIcon = document.createElement("span");
        removeIcon.textContent = "×";
        removeIcon.classList.add("remove-icon", "ml-2", "text-white", "cursor-pointer");
        removeIcon.addEventListener("click", (event) => {
          event.stopPropagation(); 
          handleTagRemove(tagItem);
        });
        tagItem.appendChild(removeIcon);
      }
    }
  
    function handleTagClick(event) {
      const tagItem = event.target.closest(".tag-item");
      if (!tagItem) return;
  
      const tagId = tagItem.getAttribute("data-tag-id");
      const container = tagItem.closest("#available-tags, #selected-tags");
  
      if (container === availableTagsContainer) {
        if (!selectedTags.has(tagId)) {
          const clonedItem = tagItem.cloneNode(true);
          clonedItem.classList.remove("hover:bg-yellow-400");
          clonedItem.classList.add("bg-yellow-400", "text-white");
          addRemoveIcon(clonedItem);  
          selectedTagsContainer.appendChild(clonedItem);
          selectedTags.add(tagId);
          tagItem.remove(); 
        }
      } else {
        handleTagRemove(tagItem);
      }
  
      updateHiddenInput();
    }
  
    function handleTagRemove(tagItem) {
      const tagId = tagItem.getAttribute("data-tag-id");
  
      tagItem.remove();
  
      const clonedItem = tagItem.cloneNode(true);
      clonedItem.classList.remove("bg-yellow-400", "text-white");
      clonedItem.classList.add("hover:bg-yellow-400");
      clonedItem.querySelector(".remove-icon")?.remove();
      availableTagsContainer.appendChild(clonedItem);
  
      selectedTags.delete(tagId);
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