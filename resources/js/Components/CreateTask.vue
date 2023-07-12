<template>
    <div>
        <div class="flex rounded-md shadow-sm">
            <input v-model="new_task_title" @keyup.enter="createTask" :placeholder="task ? 'Create subtask' : 'Add new task'" type="text" id="hs-trailing-button-add-on" name="hs-trailing-button-add-on" class="py-3 px-4 block w-full border-gray-200 shadow-sm rounded-l-md text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400">
            <button @click="createTask" type="button" class="py-3 px-4 inline-flex flex-shrink-0 justify-center items-center gap-2 rounded-r-md border border-transparent font-semibold bg-blue-500 text-white hover:bg-blue-600 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all text-sm">
                Create Task
            </button>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue';

const new_task_title = ref('');
const props = defineProps({
    task: Object,
});

const emit = defineEmits(['taskCreated']);

function createTask() {
    if (new_task_title.value) {
        emit('taskCreated', {
            title: new_task_title.value,
            parent_task_id: props.task ? props.task.id : null,
        });

        new_task_title.value = '';
    }
}

</script>