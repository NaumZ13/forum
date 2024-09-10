<template>
   <AppLayout>
       <Container>
         <div>
            <PageHeading v-text="selectedTopic ? selectedTopic.name : 'All Posts'"></PageHeading>

            <p v-if="selectedTopic" class="mt-1 text-gray-600 text-sm">{{ selectedTopic.description }}</p>

            <menu class="flex space-x-1 mt-4 overflow-x-auto pb-3 pt-1">
               <li>
                  <Pill 
                     :href="route('posts.index', { query: searchForm.query })"
                     :filled="!selectedTopic"
                     >
                        All Posts
                   </Pill>
               </li>
               <li v-for="topic in topics" :key="topic.id">
                   <Pill 
                     :href="route('posts.index', {topic: topic.slug,  query: searchForm.query})"
                     :filled="topic.id === selectedTopic?.id"
                     >
                        {{ topic.name }}
                   </Pill>
               </li>
            </menu>

            <form @submit.prevent="search" class="mt-4">
                <div>
                    <InputLabel for="query">Search</InputLabel>
                    <div class="flex space-x-2 mt-1">
                        <TextInput v-model="searchForm.query" class="w-full" id="query"/>
                        <SecondaryButton type="submit">Search</SecondaryButton>
                        <DangerButton v-if="searchForm.query" @click="clearSearch">Clear</DangerButton>
                    </div>
                </div>
            </form>
         </div>

           <ul class="divide-y mt-4">
               <li
                   v-for="post in posts.data"
                   :key="post.id"
                   class="flex flex-col items-baseline justify-between md:flex-row"
               >
                   <Link
                       :href="post.routes.show"
                       class="group block px-2 py-4"
                   >
                       <span
                           class="text-lg font-bold group-hover:text-indigo-500"
                           >{{ post.title }}</span
                       >
                       <span
                           class="block pt-1 text-sm text-gray-600 first-letter:uppercase"
                           >{{ formattedDate(post) }} ago by
                           {{ post.user.name }}</span
                       >
                   </Link>
                   <Pill :href="route('posts.index', {topic: post.topic.slug})">
                     {{ post.topic.name }}
                   </Pill>
               </li>
           </ul>

           <Pagination :meta="posts.meta" :only="['posts']" class="mt-2" />
       </Container>
   </AppLayout>
</template>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Pagination from "@/Components/Pagination.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import { formatDistance, parseISO } from "date-fns";
import { relativeDate } from "@/Utilities/Date.js";
import PageHeading from "@/Components/PageHeading.vue";
import Pill from "@/Components/Pill.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";

const props = defineProps(["posts", "topics", "selectedTopic", "query"]);

const formattedDate = (post) => relativeDate(post.created_at);

const searchForm = useForm({
    query: props.query,
    page: 1,
});
const page = usePage();

const search = () => searchForm.get(page.url);

const clearSearch = () => {
    searchForm.query = '';
    search();
};
</script>