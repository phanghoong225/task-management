<script setup>
import { router } from "@inertiajs/vue3";

defineProps({
  data: {
    type: Object,
    required: true,
  },
  pagination :{
    type: Object,
  }
});

const updatePageNumber=(link, pagination)=>{
  let pageNumber = link.url.split('=')[1];
  let url = "/tasks?page="+ pageNumber;

  if (pagination.search.value) {
    url = url + "&search=" + pagination.search.value;
  }
  if (pagination.sortDirection.value && pagination.sortType.value) {
    url = url + "&sortType=" + pagination.sortType.value;
    url = url + "&sortDirection=" + pagination.sortDirection.value;
  }
  router.visit(url);
}
</script>

<template>
  <div v-if="data.data">
    <ul id="componentContainer" class="pagination-container">
      <li>
        <button
        @click.prevent="updatePageNumber(link, pagination)"
          v-for="link in data.meta.links"
          :key="link.label"
          href=""
          :class="
            (link.active ? 'active-page ' : '') +
            'paginate-buttons number-buttons'
          "
          :disabled="link.active || link.url === null"
        >
          <span
            v-html="
              link.label.includes('Previous') || link.label.includes('Next')
                ? link.label.replace('Next', '').replace('Previous', '')
                : link.label
            "
          ></span>
        </button>
      </li>
    </ul>
  </div>
</template>

<style>
.pagination-container {
  display: flex;

  column-gap: 10px;
}

.paginate-buttons {
  height: 40px;

  width: 40px;

  border-radius: 20px;

  cursor: pointer;

  background-color: rgb(242, 242, 242);

  border: 1px solid rgb(217, 217, 217);

  color: black;
}

.paginate-buttons:hover {
  background-color: #d8d8d8;
}

.active-page {
  background-color: #3498db;

  border: 1px solid #3498db;

  color: white;
}

.active-page:hover {
  background-color: #2988c8;
}
</style>