<script setup>
import Pagination from "@/Components/Pagination.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router, useForm, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

defineProps({
  tasks: {
    type: Object,
    required: true,
  },
});

let search = ref(usePage().props.search),
  pageNumber = ref(usePage().props.page),
  sortDirection = ref(usePage().props.sortDirection),
  sortType = ref(usePage().props.sortType);

const pagination = {
  sortDirection: sortDirection,
  sortType: sortType,
  search: search,
};

let taskUrl = computed(() => {
  let url = new URL(route("tasks.index"));
  url.searchParams.append("page", pageNumber.value);

  if (search.value) {
    url.searchParams.append("search", search.value);
  }
  if (sortDirection.value && sortType.value) {
    url.searchParams.append("sortType", sortType.value);
    url.searchParams.append("sortDirection", sortDirection.value);
  }

  return url;
});

const proceedSearch = () => {
  router.visit(taskUrl.value, {
    preserveScroll: true,
    preserveState: true,
  });
};

const sortBy = (s) => {
  if (sortType.value !== s) {
    sortDirection.value = "asc";
  } else if (sortDirection.value === "asc") {
    sortDirection.value = "desc";
  } else {
    sortDirection.value = "asc";
  }
  sortType.value = s;

  proceedSearch();
};

const deleteForm = useForm({});

const statusColor = (status) => {
  if (status === "Not Urgent") {
    return "color:#12FE12";
  } else if (status === "Overdue") {
    return "color:#FF2C2C";
  } else {
    return "color:#0000FF";
  }
};

const deleteTask = (taskId)=> {
  if(confirm('Are you sure you want to delete this task?')){
    deleteForm.delete(route('tasks.destroy', taskId));
  }
}
</script>


<template>
  <Head title="Task Management" />
  <AuthenticatedLayout>
    <div>
      <div class="card mb-3" style="padding-left: 5%; padding-right: 5%">
        <div class="row">
          <div class="card-body col-lg-4">
            <form @submit.prevent="search">
              <div class="input-group mb-3">
                <input
                  v-model="search"
                  type="text"
                  class="form-control"
                  placeholder="Task Name"
                  aria-label="Task Name"
                  aria-describedby="button-addon2"
                />
                <button
                  v-on:click="proceedSearch"
                  class="btn btn-primary"
                  type="button"
                  id="button-addon2"
                >
                  Search
                </button>
              </div>
            </form>
          </div>
          <div class="card-body col-lg-4"></div>
          <div class="card-body col-lg-4">
            <form class="d-flex" style="float: right">
              <div class="input-group mb-3">
                <Link
                  :href="route('tasks.create')"
                  class="btn btn-primary my-2 my-sm-0"
                  type="button"
                  style="float: right"
                >
                  New Task
                </Link>
              </div>
            </form>
          </div>
        </div>
        <div>
          <div class="table-responsive-sm">
            <table
              role="table"
              aria-colcount="4"
              class="table b-table"
              id="__BVID__1218"
            >
              <thead role="rowgroup" class="">
                <tr role="row" class="table-primary">
                  <th
                    role="columnheader"
                    scope="col"
                    tabindex="0"
                    aria-colindex="1"
                    class="position-relative b-table-sort-icon-left"
                  >
                    <div>#</div>
                  </th>
                  <th
                    role="columnheader"
                    scope="col"
                    tabindex="0"
                    aria-colindex="2"
                    class="position-relative b-table-sort-icon-left"
                  >
                    <div>Task Name</div>
                  </th>
                  <th
                    role="columnheader"
                    scope="col"
                    tabindex="0"
                    aria-colindex="3"
                    class="position-relative b-table-sort-icon-left"
                  >
                    <div>Description</div>
                  </th>
                  <th
                    role="columnheader"
                    scope="col"
                    tabindex="0"
                    aria-colindex="4"
                    :aria-sort="
                      sortType === 'dueDate'
                        ? sortDirection === 'asc'
                          ? 'ascending'
                          : 'descending'
                        : 'none'
                    "
                    class="position-relative b-table-sort-icon-left"
                    v-on:click="sortBy('dueDate')"
                  >
                    <div>Due Date</div>
                  </th>
                  <th
                    role="columnheader"
                    scope="col"
                    tabindex="0"
                    aria-colindex="5"
                    class="position-relative b-table-sort-icon-left"
                  >
                    <div>Status</div>
                  </th>
                  <th
                    role="columnheader"
                    scope="col"
                    tabindex="0"
                    aria-colindex="6"
                    :aria-sort="
                      sortType === 'created_at'
                        ? sortDirection === 'asc'
                          ? 'ascending'
                          : 'descending'
                        : 'none'
                    "
                    class="position-relative b-table-sort-icon-left"
                    v-on:click="sortBy('created_at')"
                  >
                    <div>Created Date</div>
                  </th>
                  <th
                    role="columnheader"
                    scope="col"
                    tabindex="0"
                    aria-colindex="7"
                    class="position-relative b-table-sort-icon-left"
                  >
                    <div>Assignee</div>
                  </th>
                  <th
                    role="columnheader"
                    scope="col"
                    aria-colindex="8"
                    class="position-relative"
                  >
                    <div>Action</div>
                  </th>
                </tr>
              </thead>
              <tbody role="rowgroup">
                <tr
                  v-for="task in tasks.data"
                  :key="task.id"
                  class="table-light"
                >
                  <th scope="row" aria-colindex="1" role="cell">
                    {{ task.id }}
                  </th>
                  <th
                    scope="row"
                    aria-colindex="2"
                    role="cell"
                    style="width: 10%"
                  >
                    {{ task.name }}
                  </th>
                  <th
                    scope="row"
                    aria-colindex="3"
                    role="cell"
                    style="width: 40%"
                  >
                    {{ task.description }}
                  </th>
                  <th scope="row" aria-colindex="4" role="cell">
                    {{ task.dueDate }}
                  </th>
                  <th scope="row" aria-colindex="5" role="cell" :style="statusColor(task.status)">
                    {{ task.status }}
                  </th>
                  <th scope="row" aria-colindex="6" role="cell">
                    {{ task.created_at }}
                  </th>
                  <th scope="row" aria-colindex="7" role="cell">
                    {{ task.assignee.name }}
                  </th>
                  <th scope="row" aria-colindex="8" role="cell">
                    <Link
                      :href="route('tasks.edit', task.id)"
                      type="button"
                      class="btn btn-outline-primary"
                    >
                      Edit
                    </Link>
                    &nbsp;
                    <Link
                      @click="deleteTask(task.id)"
                      type="button"
                      class="btn btn-outline-secondary"
                    >
                      Delete
                    </Link>
                  </th>
                </tr>
              </tbody>
              <tbody v-if="tasks.data.length === 0">
                <tr class="table-light no-records">
                  <td colspan="8">No record found.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer text-muted">
          <Pagination :data="tasks" :pagination="pagination" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>