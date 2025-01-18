console.log("ffffffffffff");

document.addEventListener('DOMContentLoaded', function () {
    const toggleHeader = document.getElementById('toggleFormHeader');
    const courseForm = document.getElementById('courseForm');
    const toggleIcon = document.getElementById('toggleIcon');
    const newCourseBtn = document.getElementById('newCourseBtn');

    courseForm.classList.add('hidden');

    toggleHeader.addEventListener('click', function () {
        courseForm.classList.toggle('hidden');
        toggleIcon.style.transform = courseForm.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
    });

    newCourseBtn.addEventListener('click', function () {
        courseForm.classList.remove('hidden');
        toggleIcon.style.transform = 'rotate(180deg)';
        document.getElementById('add').scrollIntoView({ behavior: 'smooth' });
    });

    if (window.location.hash === '#add') {
        courseForm.classList.remove('hidden');
        toggleIcon.style.transform = 'rotate(180deg)';
        document.getElementById('add').scrollIntoView({ behavior: 'smooth' });
    }
});

const selectElement = document.getElementById('content-type');
const videoUploadDiv = document.getElementById('video-upload');
const documentUploadDiv = document.getElementById('document-upload');

selectElement.addEventListener('change', (event) => {
    const selectedValue = event.target.value;
    console.log('Selected value:', selectedValue); 

    videoUploadDiv.classList.add('hidden');
    documentUploadDiv.classList.add('hidden');

    if (selectedValue === 'video') {
        videoUploadDiv.classList.remove('hidden');
    } else if (selectedValue === 'document') {
        documentUploadDiv.classList.remove('hidden');
    }
});


const fileInput = document.getElementById('file-upload-video');
    const fileNameDisplay = document.getElementById('file-name');

    fileInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            fileNameDisplay.textContent = `Selected Video: ${file.name}`;
        } else {
            fileNameDisplay.textContent = '';
        }
    });

    const documentInput = document.getElementById('file-upload-document');
    const documentFileNameDisplay = document.getElementById('document-file-name');

    documentInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            documentFileNameDisplay.textContent = `Selected file: ${file.name}`;
        } else {
            documentFileNameDisplay.textContent = ''; 
        }
    });    


    const thumbnailInput = document.getElementById('file-upload-thumbnail');
    const thumbnailFileNameDisplay = document.getElementById('thumbnail-file-name');

    thumbnailInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            thumbnailFileNameDisplay.textContent = `Selected file: ${file.name}`;
        } else {
            thumbnailFileNameDisplay.textContent = ''; 
        }
    }); 


    // document.addEventListener("DOMContentLoaded", () => {
    //     const availableTagsContainer = document.getElementById("available-tags");
    //     const selectedTagsContainer = document.getElementById("selected-tags");
    //     const selectedTagsHiddenInput = document.getElementById("selected-tags-hidden");
    //     const selectedTags = new Set(); 
    
    //     function updateHiddenInput() {
    //         selectedTagsHiddenInput.value = Array.from(selectedTags).join(",");
    //     }
    
    //     [availableTagsContainer, selectedTagsContainer].forEach((container) => {
    //         container.addEventListener("click", (event) => {
    //             const tagItem = event.target.closest(".tag-item");
    //             if (!tagItem) return;
    
    //             const tagId = tagItem.getAttribute("data-tag-id");
    
    //             if (container === availableTagsContainer) {
    //                 tagItem.classList.remove("hover:bg-yellow-400");
    //                 tagItem.classList.add("bg-yellow-400", "text-white");
    //                 const removeIcon = document.createElement("span");
    //                 removeIcon.textContent = "×";
    //                 removeIcon.classList.add("remove-icon", "ml-2", "text-white", "cursor-pointer");
    //                 tagItem.appendChild(removeIcon);
    //                 selectedTagsContainer.appendChild(tagItem);
    //                 selectedTags.add(tagId);
    //             } else if (container === selectedTagsContainer) {
    //                 tagItem.classList.remove("bg-yellow-400", "text-white");
    //                 tagItem.classList.add("hover:bg-yellow-400");
    //                 const removeIcon = tagItem.querySelector(".remove-icon");
    //                 if (removeIcon) removeIcon.remove();
    //                 availableTagsContainer.appendChild(tagItem);
    //                 selectedTags.delete(tagId);
    //             }
    
    //             updateHiddenInput();
    //         });
    //     });
    // });


    document.addEventListener("DOMContentLoaded", () => {
        const availableTagsContainer = document.getElementById("available-tags");
        const selectedTagsContainer = document.getElementById("selected-tags");
        const selectedTagsHiddenInput = document.getElementById("selected-tags-hidden");
        const selectedTags = new Set(); 
    
        function updateHiddenInput() {
            selectedTagsHiddenInput.value = Array.from(selectedTags).join(","); // Update hidden input value
        }
    
        [availableTagsContainer, selectedTagsContainer].forEach((container) => {
            container.addEventListener("click", (event) => {
                const tagItem = event.target.closest(".tag-item");
                if (!tagItem) return;
    
                const tagId = tagItem.getAttribute("data-tag-id");
    
                if (container === availableTagsContainer) {
                    // Move tag to "Selected Tags"
                    tagItem.classList.remove("hover:bg-yellow-400");
                    tagItem.classList.add("bg-yellow-400", "text-white");
                    const removeIcon = document.createElement("span");
                    removeIcon.textContent = "×";
                    removeIcon.classList.add("remove-icon", "ml-2", "text-white", "cursor-pointer");
                    tagItem.appendChild(removeIcon);
                    selectedTagsContainer.appendChild(tagItem);
                    selectedTags.add(tagId);
                } else if (container === selectedTagsContainer) {
                    // Move tag back to "Available Tags"
                    tagItem.classList.remove("bg-yellow-400", "text-white");
                    tagItem.classList.add("hover:bg-yellow-400");
                    const removeIcon = tagItem.querySelector(".remove-icon");
                    if (removeIcon) removeIcon.remove();
                    availableTagsContainer.appendChild(tagItem);
                    selectedTags.delete(tagId);
                }
    
                updateHiddenInput();
            });
        });
    });

    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const hours = parseInt(document.getElementById('duration_hours').value || '0');
        const minutes = parseInt(document.getElementById('duration_minutes').value || '0');
        
        let durationString = '';
        
        if (hours > 0) {
            durationString += `${hours} hour${hours !== 1 ? 's' : ''}`;
        }
        
        if (minutes > 0) {
            if (hours > 0) durationString += ' ';
            durationString += `${minutes} min${minutes !== 1 ? 's' : ''}`;
        }
        
        if (durationString === '') {
            durationString = '0 mins';
        }
        
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'duration';
        hiddenInput.value = durationString;
        this.appendChild(hiddenInput);
        
        this.submit();
    });
    