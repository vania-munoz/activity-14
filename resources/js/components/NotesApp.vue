<template>
  <div class="container">
    <h1>My Notes</h1>
    <p>Activity 14 Laravel & Vue CRUD</p>

    <!-- formulario para crear nota -->
    <div class="form-container">
      <h3>Create New Note</h3>
      <input v-model="newNote.title" placeholder="Title" />
      <input v-model="newNote.author" placeholder="Author" />
      <textarea v-model="newNote.body" placeholder="Note body..."></textarea>
      
      <select v-model="newNote.classification">
        <option value="personal">Personal</option>
        <option value="work">Work</option>
        <option value="school">School</option>
        <option value="others">Others</option>
      </select>
      
      <button @click="saveNote">Add Note</button>
    </div>

    <hr />

    <button @click="loadNotes">Reload Notes</button>

    <!-- lista de notas -->
    <ul>
      <li v-for="note in notes" :key="note.id" class="note-item">
        <div>
          <strong>{{ note.title }}</strong> 
          <small>({{ note.classification }})</small>
          <p>By: {{ note.author }} | {{ new Date(note.created_at).toLocaleString() }}</p>
          <p>{{ note.body }}</p>
        </div>

        <div class="actions">
          <!-- Boton para editar-->
          <button @click="editNote(note)" class="edit-btn">Edit</button>
          <!-- Boton para eliminar -->
          <button @click="deleteNote(note.id)" class="delete-btn">Delete</button>
        </div>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const notes = ref([]);
const newNote = ref({
  title: '',
  author: '',
  body: '',
  classification: 'personal'
});

//cargar notas
async function loadNotes() {
  const response = await fetch('/api/notes');
  notes.value = await response.json();
}

//guardar nota
async function saveNote() {
  if (!newNote.value.title || !newNote.value.author) return alert("Fill title and author");

  await fetch('/api/notes', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify(newNote.value)
  });

  //para limpiar formulario y recargar
  newNote.value = { title: '', author: '', body: '', classification: 'personal' };
  await loadNotes();
}

//eliminar nota
async function deleteNote(id) {
  if (confirm("Are you sure?")) {
    await fetch(`/api/notes/${id}`, { method: 'DELETE' });
    await loadNotes();
  }
}

//editar nota
async function editNote(note) {
  const newTitle = prompt("Edit Title:", note.title);
  if (newTitle) {
    await fetch(`/api/notes/${note.id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ ...note, title: newTitle })
    });
    await loadNotes();
  }
}

onMounted(() => { loadNotes(); });
</script>

<style scoped>
    .container{
        max-width: 700px;
        margin: 40px auto;
        font-family: Arial, Helvetica, sans-serif;
    }
    h1{
        margin-bottom: 8px;
    }
    ul{
        list-style: none;
        padding: 0;
    }
    li{
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }
    button{
        padding: 6px 10px;
        cursor: pointer;
    }
    
</style>

