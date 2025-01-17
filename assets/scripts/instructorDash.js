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
// Récupération des éléments
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