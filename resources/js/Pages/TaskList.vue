<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CreateTask from '@/Components/CreateTask.vue';
import Tasks from '@/Components/Tasks.vue';

import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    list: {
        type: Object,
    },
});

const tasks = ref(props.list.data);
const success_message = ref('');
const error_message = ref('');

function updateStatus(details) {
    let task = details.task;
    task.status = details.status;

    axios.post('/tasks/store', task)
        .then(response => {
            error_message.value = '';
            success_message.value = response.data.message;

            setTimeout(() => {
                success_message.value = '';
            }, 3000);
        })
        .catch(error => {
            error_message.value = '';
            let new_error = '';

            error.response.data.errors.forEach((line) => {
                new_error += line + '<br>';
            })

            error_message.value = new_error;
        });
}
function createTask(details) {
    axios.post('/tasks/store', details)
        .then(response => {
            if (!response.data.task.parent_task_id) {
                tasks.value.push(response.data.task);
            } else {
                tasks.value.forEach((task) => {
                    if (task.id == response.data.task.parent_task_id) {
                        task.subtasks.push(response.data.task);
                    }
                })
            }

            setTimeout(() => {
                success_message.value = '';
            }, 3000);
        })
        .catch(error => {
            console.log(error);
        });
}
function taskDeleted(task) {
    axios.delete('/tasks/destroy/'+task.id)
        .then(response => {
            router.visit('/tasks');
            // tasks.value = props.list.data;
        })
        .catch(error => {
            console.log(error);
        });
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-5 bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert" v-if="success_message">
                    <div class="flex">
                        <p class="text-sm">{{ success_message }}</p>
                    </div>
                </div>

                <div class="mb-5 bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md" role="alert" v-if="error_message">
                    <div class="flex">
                        <p class="text-sm" v-html="error_message"></p>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <Tasks :list="list" @task-created="createTask" @status-changed="updateStatus" @task-deleted="taskDeleted"></Tasks>

                    <p v-if="tasks.length == 0">No tasks have been created yet.</p>

                    <hr class="mt-5">
                    <div class="mt-5">
                        <CreateTask @task-created="createTask"></CreateTask>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
