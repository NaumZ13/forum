
<template>
   <AppLayout>
    <Container>
      <ul class="divide-y">
         <li v-for="post in posts.data" :key="post.id" class="px-2 py-4">
            <Link :href="route('posts.show', post.id)" class="group">
               <span class="font-bold text-lg group-hover:text-indigo-500 px-2 py-4">
                  {{ post.title  }}
               </span> 
               <span class="block mt-1 text-sm text-gray-600">{{ formattedDate(post) }} ago by {{ post.user.name }}</span>
            </Link>
         </li>
      </ul>
      <Pagination :meta="posts.meta"/>
    </Container>
   </AppLayout>
</template>
<script setup>

import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';
import { relativeDate } from '@/Utilities/Date';

const props = defineProps(['posts']);

const formattedDate = (post) => relativeDate(post.created_at);

</script>