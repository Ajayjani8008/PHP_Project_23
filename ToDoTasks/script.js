
const taskInput = document.getElementById("taskInput");
const addButton = document.getElementById("addButton");
const taskList = document.getElementById("taskList");

addButton.addEventListener("click", addTask);

function addTask() {
    const taskText = taskInput.value;
    if (taskText.trim() !== "") {
        const li = document.createElement("li");
        li.innerText = taskText;
        li.addEventListener("click", toggleDone);
        taskList.appendChild(li);
        taskInput.value = "";
    }
}

function toggleDone(event) {
    const li = event.target;
    li.classList.toggle("done");
}
