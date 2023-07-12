## Install Project

To run the application follow these steps:

- Create a MySQL database called `simple_tasklist`
- Rename the `.env.example` file to .env and check that the connection settings are correct
- Run `composer install`
- Run `npm install`
- Run `npm run dev`
- Run `php artisan serve`
- The application should become available in `http://127.0.0.1:8000`
- Register a new user

## Design choices

Fresh Laravel 10 project with Laravel Breeze & Vue, which also uses Inertia and TailwindCSS.

That configuration already includes the user registration and login forms. That allowed me to focus only on the specific requirements of this project, which is the management of tasks and subtasks.

I started by creating a Model with its corresponding Migration. After that I created a Controller with the endpoints that would be needed to manage the CRUD operations. The last step was to make sure that the routing was correct.

The Vue front-end was done afterwards, and 3 components were created (CreateTask, Task and Tasks), along with a Page called TaskList.vue.

Once the front-end was completed, the notification UnfinishedTaskNotification was created and connected to the form that updates the tasks. The notification will be sent using a Queue and a default delay of 24 hours. But before actually sending the notification, it will call the shouldSend() method to make sure that the task is still in the "in_progress" status.

A proper Queue worker should be used when deploying the application, otherwise the delay won't be taken into consideration and the notification will be sent straightaway.

## Challenges/Trade-offs

I wanted to make sure I used the latest stable Laravel version (10.10 in this case), and I knew there were tools that would provide all the scaffolding for this type of application, so I tried to use them as well. That's why I chose Laravel Breeze.

The biggest challenge has been the usage of Tailwind, because I'm used to using Twitter Bootstrap, and in Tailwind there are no pre-fabricated components: they need to be created one by one. I used it because Laravel Breeze uses Tailwind by default and I wanted to follow Laravel's recommended way instead of installing external packages.

Since the primary interest of this small application was just to showcase the usage of Laravel with a Vue front-end, many features and usability enhancements have been ommited, although they would be absolutely necessary if the application was to be deployed in a real live environment.