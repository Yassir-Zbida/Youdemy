document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");
  const selectedTagsHiddenInput = document.getElementById("selected-tags-hidden");
  const availableTagsContainer = document.getElementById("available-tags");
  const selectedTagsContainer = document.getElementById("selected-tags");
  const durationInput = document.getElementById("duration");

  function updateHiddenInput() {
    const selectedTags = Array.from(selectedTagsContainer.querySelectorAll(".tag-item"))
      .map((tag) => tag.getAttribute("data-tag-id"));
    selectedTagsHiddenInput.value = selectedTags.join(",");
  }

  form.addEventListener("submit", (event) => {
    if (!validateForm()) {
      event.preventDefault();
      return;
    }
    updateHiddenInput();

    const hours = parseInt(durationInput.value || "0");
    const durationString = hours > 0 ? `${hours} hour${hours !== 1 ? "s" : ""}` : "0 hours";

    const durationInputElement = document.createElement("input");
    durationInputElement.type = "hidden";
    durationInputElement.name = "duration";
    durationInputElement.value = durationString;
    form.appendChild(durationInputElement);

    console.log("Form is being submitted");
  });

  function validateForm() {
    const title = document.getElementById("title").value.trim();
    const description = document.getElementById("description").value.trim();

    if (!title || !description) {
      alert("Title and Description are required.");
      return false;
    }

    if (selectedTagsContainer.children.length === 0) {
      alert("At least one tag must be selected.");
      return false;
    }

    return true;
  }

  const contentTypeSelect = document.getElementById("content-type");
  const videoUploadSection = document.getElementById("video-upload");
  const documentUploadSection = document.getElementById("document-upload");

  function handleContentTypeChange() {
    const selectedContentType = contentTypeSelect.value;

    if (selectedContentType === "video") {
      videoUploadSection.classList.remove("hidden");
      documentUploadSection.classList.add("hidden");
    } else if (selectedContentType === "document") {
      documentUploadSection.classList.remove("hidden");
      videoUploadSection.classList.add("hidden");
    } else {
      videoUploadSection.classList.add("hidden");
      documentUploadSection.classList.add("hidden");
    }
  }

  if (contentTypeSelect) {
    contentTypeSelect.addEventListener("change", handleContentTypeChange);
    handleContentTypeChange();
  }

  const fileInputHandlers = [
    { inputId: "file-upload-video", displayId: "file-name", prefix: "Selected Video: " },
    { inputId: "file-upload-document", displayId: "document-file-name", prefix: "Selected File: " },
    { inputId: "file-upload-thumbnail", displayId: "thumbnail-file-name", prefix: "Selected Thumbnail: " },
  ];

  fileInputHandlers.forEach(({ inputId, displayId, prefix }) => {
    const input = document.getElementById(inputId);
    const display = document.getElementById(displayId);

    if (input && display) {
      input.addEventListener("change", (event) => {
        const file = event.target.files[0];
        display.textContent = file ? `${prefix}${file.name}` : "";
      });
    } else {
      console.warn(`Element with ID ${inputId} or ${displayId} is missing.`);
    }
  });
});
