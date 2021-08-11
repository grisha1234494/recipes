<template>
    <div>
        <div class="max-w-3xl bg-white rounded-md shadow" :data-comment-id="'comment-' + comment.id">
            <div class="relative p-6 mb-8">
                <div class="absolute flex left-0 right-0 justify-between px-6" style="bottom: -.55rem">
                    <div>
                        <span class="py-1 px-2 bg-gray-400 rounded-3xl">{{ comment.created_at | displayDateTime }}</span>
                    </div>
                    <div>
                        <a class="py-1 px-2 bg-indigo-400 rounded-3xl text-white" href="#" @click.prevent="commentReply()">reply</a>
                    </div>
                </div>
                <div>
                    <p>
                        <span class="font-semibold">{{ comment.user_id }} : </span>
                        <span>{{ comment.text }}</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="sm:pl-6 lg:pl-8" v-if="comment.children.length > 0">
            <comment 
                v-for="child in comment.children" 
                :key="child.id" 
                :comment="child" 
                :ref="'comment-' + child.id" 
            />
        </div>
    </div>
</template>

<script>
    import { eventBus } from '@/app'

    export default {
        name: 'Comment',
        props :[
            'comment',
        ],
        mounted() {

        },
        methods: {
            commentReply() {
                eventBus.$emit('commentReply', this.comment)
            },
        },
    }
</script>