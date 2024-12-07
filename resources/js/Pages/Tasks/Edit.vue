<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router, useForm, usePage } from "@inertiajs/vue3";
import Datepicker from "@vuepic/vue-datepicker";
import InputError from "@/Components/InputError.vue";
import "@vuepic/vue-datepicker/dist/main.css";

defineProps({
  users: {
    type: Object,
    required: true,
  },
  task: {
    type: Object,
    required: true,
  },
});

let task = usePage().props.task.data;

const form = useForm({
  name: task.name,
  description: task.description,
  dueDate: task.dueDate,
  assignee_id: task.assignee.id,
});

const format = (date) => {
  const day = date.getDate();
  const month = date.getMonth() + 1;
  const year = date.getFullYear();

  return `${day}-${month}-${year}`;
};

const updateTask = () => {
  form.put(route("tasks.update", task.id));
};
</script>

<template>
  <Head title="Edit Task" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Update Task
      </h2>
    </template>
    <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
      <div class="card-body">
        <div class="row">
          <div class="col-lg-6">
            <div class="bs-component">
              <form @submit.prevent="updateTask">
                <fieldset>
                  <div>
                    <label for="taskName" class="form-label mt-4"
                      >Task Name</label
                    >
                    <input
                      v-model="form.name"
                      type="text"
                      class="form-control"
                      id="staticEmail"
                    />
                    <InputError :message="form.errors.name" class="mt-2" />
                  </div>
                  <div>
                    <label for="description" class="form-label mt-4"
                      >Description</label
                    >
                    <input
                      v-model="form.description"
                      type="text"
                      class="form-control"
                      id="description"
                    />
                    <InputError
                      :message="form.errors.description"
                      class="mt-2"
                    />
                  </div>
                  <div>
                    <label for="userSelection" class="form-label mt-4"
                      >Assignee</label
                    >
                    <select
                      class="form-select"
                      id="userSelection"
                      v-model="form.assignee_id"
                    >
                      <option value="">------------------------</option>
                      <option
                        v-for="user in users.data"
                        :key="user.id"
                        :value="user.id"
                      >
                        {{ user.name }}
                      </option>
                    </select>
                    <InputError
                      :message="form.errors.assignee_id"
                      class="mt-2"
                    />
                  </div>
                  <div>
                    <label for="dueDate" class="form-label mt-4"
                      >Due Date</label
                    >
                    <Datepicker
                      v-model="form.dueDate"
                      :enable-time-picker="false"
                      :format="format"
                    />
                    <InputError :message="form.errors.dueDate" class="mt-2" />
                  </div>

                  <hr />
                  &nbsp;
                  <div class="modal-footer">
                    <Link
                      :href="route('tasks.index')"
                      type="button"
                      class="btn btn-secondary"
                      >Back</Link
                    >
                    &nbsp;
                    <button type="submit" class="btn btn-primary">
                      Update
                    </button>
                  </div>
                </fieldset>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>