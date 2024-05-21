<div class="col-md-12">
    <div class="signatureCompleted-pad border-1">
        <canvas class="border " id="signatureCompletedCanvas"></canvas>
        <div class="buttons">
            <button type="button" id="clearBtn">Clear</button>
            <button type="button" id="saveBtn">Save</button>
        </div>
    </div>
    <input class="d-none" type="file" id="signatureCompletedInput" name="signatureCompleted" accept="image/png"
        required>
</div>

<script>
    const canvas = document.getElementById('signatureCompletedCanvas');
    const ctx = canvas.getContext('2d');
    const clearBtn = document.getElementById('clearBtn');
    const saveBtn = document.getElementById('saveBtn');
    const signatureCompletedInput = document.getElementById('signatureCompletedInput');
    const signatureCompletedForm = document.getElementById('signatureCompletedForm');

    canvas.width = 450;
    canvas.height = 200;

    let drawing = false;

    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('touchstart', startDrawing);
    canvas.addEventListener('touchend', stopDrawing);
    canvas.addEventListener('touchmove', draw);

    clearBtn.addEventListener('click', clearCanvas);
    saveBtn.addEventListener('click', saveSignature);

    function startDrawing(event) {
        drawing = true;
        ctx.beginPath();
        ctx.moveTo(getX(event), getY(event));
        event.preventDefault();
    }

    function stopDrawing(event) {
        drawing = false;
        event.preventDefault();
    }

    function draw(event) {
        if (!drawing) return;

        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#000';

        ctx.lineTo(getX(event), getY(event));
        ctx.stroke();
        event.preventDefault();
    }

    function getX(event) {
        if (event.touches && event.touches.length > 0) {
            return event.touches[0].clientX - canvas.getBoundingClientRect().left;
        } else {
            return event.clientX - canvas.getBoundingClientRect().left;
        }
    }

    function getY(event) {
        if (event.touches && event.touches.length > 0) {
            return event.touches[0].clientY - canvas.getBoundingClientRect().top;
        } else {
            return event.clientY - canvas.getBoundingClientRect().top;
        }
    }

    function clearCanvas() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        // Clear the file input
        signatureCompletedInput.value = null;
    }

    function saveSignature() {
        const dataURL = canvas.toDataURL('image/png');
        const blob = dataURItoBlob(dataURL);
        const file = new File([blob], 'signatureCompleted.png', {
            type: 'image/png'
        });

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        signatureCompletedInput.files = dataTransfer.files;

        // signatureCompletedForm.submit();
    }

    function dataURItoBlob(dataURI) {
        const byteString = atob(dataURI.split(',')[1]);
        const mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
        const ab = new ArrayBuffer(byteString.length);
        const ia = new Uint8Array(ab);
        for (let i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }
        return new Blob([ab], {
            type: mimeString
        });
    }
</script>
