const noteColors = ['#f44336', '#2196F3', '#4CAF50', '#FF9800', '#9C27B0'];

function getContrastYIQ(hexcolor) {
    hexcolor = hexcolor.replace("#", "");
    const r = parseInt(hexcolor.substr(0, 2), 16);
    const g = parseInt(hexcolor.substr(2, 2), 16);
    const b = parseInt(hexcolor.substr(4, 2), 16);
    const yiq = ((r * 299) + (g * 587) + (b * 114)) / 1000;
    return (yiq >= 128) ? '#000' : '#FFF';
}

function makeNoteDraggable(el) {
    let offsetX, offsetY;
    el.onmousedown = function (e) {
        if (e.target.classList.contains('close-btn')) return;
        offsetX = e.clientX - el.offsetLeft;
        offsetY = e.clientY - el.offsetTop;
        document.onmousemove = function (e) {
            el.style.left = (e.clientX - offsetX) + 'px';
            el.style.top = (e.clientY - offsetY) + 'px';
        }
        document.onmouseup = function () {
            document.onmousemove = null;
            document.onmouseup = null;

            const id = el.dataset.noteId;
            const noteData = JSON.parse(localStorage.getItem('temp_notes') || '{}');
            if (noteData[id]) {
                noteData[id].top = el.style.top;
                noteData[id].left = el.style.left;
                noteData[id].width = el.style.width;
                noteData[id].height = el.style.height;
                localStorage.setItem('temp_notes', JSON.stringify(noteData));
            }
        }
    }
}

function createNote(id, content = '', top = '100px', left = '100px', width = '200px', height = '150px', color = '') {
    const note = document.createElement('div');
    note.className = 'note';
    note.contentEditable = true;
    note.innerText = content;
    note.dataset.noteId = id;
    note.style.top = top;
    note.style.left = left;
    note.style.width = width;
    note.style.height = height;

    if (!color) {
        color = noteColors[Math.floor(Math.random() * noteColors.length)];
    }

    note.style.backgroundColor = color;
    note.style.color = getContrastYIQ(color);

    const closeBtn = document.createElement('div');
    closeBtn.className = 'close-btn';
    closeBtn.innerHTML = '×';
    closeBtn.title = 'Close (or press Ctrl + Q)';
    closeBtn.onclick = function (e) {
        e.stopPropagation();
        note.remove();
        const notes = JSON.parse(localStorage.getItem('temp_notes') || '{}');
        delete notes[id];
        localStorage.setItem('temp_notes', JSON.stringify(notes));
    };
    note.appendChild(closeBtn);

    document.body.appendChild(note);
    makeNoteDraggable(note);

    note.addEventListener('input', () => {
        const notes = JSON.parse(localStorage.getItem('temp_notes') || '{}');
        notes[id] = {
            content: note.innerText,
            top: note.style.top,
            left: note.style.left,
            width: note.style.width,
            height: note.style.height,
            color: color
        };
        localStorage.setItem('temp_notes', JSON.stringify(notes));
    });
}

function loadNotes() {
    const notes = JSON.parse(localStorage.getItem('temp_notes') || '{}');
    for (const [id, note] of Object.entries(notes)) {
        createNote(id, note.content, note.top, note.left, note.width, note.height, note.color);
    }
}

document.addEventListener('keydown', function (e) {
    const isInput = document.activeElement && (
        document.activeElement.tagName === 'INPUT' ||
        document.activeElement.tagName === 'TEXTAREA' ||
        document.activeElement.isContentEditable
    );

    if (!isInput && e.key.toLowerCase() === 'q' && !e.ctrlKey) {
        const id = 'note_' + Date.now();
        createNote(id);

        const notes = JSON.parse(localStorage.getItem('temp_notes') || '{}');
        notes[id] = {
            content: '',
            top: '100px',
            left: '100px',
            width: '200px',
            height: '150px',
            color: ''
        };
        localStorage.setItem('temp_notes', JSON.stringify(notes));
    }

    if (e.ctrlKey && e.key.toLowerCase() === 'q') {
        e.preventDefault();
        const notes = document.querySelectorAll('.note');
        if (notes.length > 0) {
            const lastNote = notes[notes.length - 1];
            const id = lastNote.dataset.noteId;
            lastNote.remove();
            const data = JSON.parse(localStorage.getItem('temp_notes') || '{}');
            delete data[id];
            localStorage.setItem('temp_notes', JSON.stringify(data));
        }
    }
});

window.onload = loadNotes;