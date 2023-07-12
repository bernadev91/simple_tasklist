<script setup>
import { ref } from 'vue';
import CreateTask from '@/Components/CreateTask.vue';

const props = defineProps({
    task: Object,
});
const emit = defineEmits(['taskCreated', 'statusChanged', 'taskDeleted']);

const create_subtask = ref(false);

function createTask(details) {
    emit('taskCreated', details);
}
function updateStatus(status) {
    showDropdown.value = false;

    emit('statusChanged', {
        task: props.task,
        status: status,
    });
}
function deleteTask() {
    showDropdown.value = false;

    if (confirm('Are you sure you want to delete this task?')) {
        emit('taskDeleted', props.task);
    }
}
const statuses = [
    { value: 'todo', label: 'To Do' },
    { value: 'in_progress', label: 'In Progress' },
    { value: 'done', label: 'Done' },
];

let showDropdown = ref(false);

</script>
<template>
    <div class="mb-2">
        <span class="text-xs py-0 px-2 shadow-md no-underline rounded-full bg-blue-600 text-white font-sans font-semibold text-sm border-blue btn-primary hover:text-white hover:bg-blue-light focus:outline-none active:shadow-none mr-2">{{ task.creator }}</span>
        <span :style="{ 'text-decoration': task.status == 'done' ? 'line-through' : '' }">{{ task.title }}</span>

        <em v-if="task.status == 'in_progress'"> (in progress)</em>

        <div class="inline-block relative">
            <button @click="showDropdown = !showDropdown" data-dropdown-toggle="dropdownBottom" data-dropdown-placement="bottom" class="ms-5 text-stone-400 font-medium rounded-lg text-sm inline-flex items-center" type="button">Actions<svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
            </svg></button>

            <!-- Dropdown menu -->
            <div :class="{hidden: !showDropdown}" class="absolute z-10 bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownBottomButton">
                    <span v-for="status in statuses">
                        <li v-if="status.value != task.status">
                            <a href="#" @click="updateStatus(status.value)" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Mark as "{{ status.label }}"</a>
                        </li>
                    </span>
                    <li>
                        <a href="#" @click="create_subtask = true; showDropdown = false;" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Create SubTask</a>
                    </li>
                    <li>
                        <a href="#" @click="deleteTask" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Delete Task</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="overflow-auto ms-10 mt-3" v-if="create_subtask">
            <CreateTask @task-created="createTask" :task="task"></CreateTask>
        </div>
    </div>
</template>
