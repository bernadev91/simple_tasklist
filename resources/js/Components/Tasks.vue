<script setup>
import Task from '@/Components/Task.vue';

const props = defineProps({
    list: Object,
});
const emit = defineEmits(['taskCreated', 'statusChanged', 'taskDeleted']);

function createTask(details) {
    emit('taskCreated', details);
}
function updateStatus(details) {
    emit('statusChanged', details);
}
function taskDeleted(task) {
    emit('taskDeleted', task);
}
</script>
<template>
    <div v-for="task in list.data" :key="task.id" >
        <Task :task="task" @task-created="createTask" @status-changed="updateStatus" @task-deleted="taskDeleted"></Task>

        <div v-if="task.subtasks.length > 0" class="ms-10">
            <Task :task="subtask" v-for="subtask in task.subtasks" @task-created="createTask" @status-changed="updateStatus" @task-deleted="taskDeleted"></Task>
        </div>
    </div>
</template>
