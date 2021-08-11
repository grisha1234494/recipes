<template>
    <div>
        <p class="font-semibold text-xl text-gray-800 leading-tight">
            Comments
        </p>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8" v-if="treeComments.length > 0">
            <comment 
                v-for="treeComment in treeComments" 
                :key="treeComment.id" 
                :comment="treeComment" 
                :ref="'comment-' + treeComment.id" 
            />
        </div>
        <p class="font-semibold text-xl text-gray-800 leading-tight">
            Add comment
        </p>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="max-w-3xl bg-white rounded-md shadow">
                <form @submit.prevent="commentStore" ref="commentForm">
                    <div class="p-6">
                        <transition name="slide-fade">
                            <div v-if="commentReplyTo.id" class="flex justify-between -mx-6 -mt-6 p-6 mb-4 rounded-t-md bg-gray-200">
                                <div>
                                    Reply to : <a href="#" @click.prevent="commentScroll('comment-' + commentReplyTo.id)" class="italic text-gray-600">{{ commentReplyTo.text }}</a>
                                </div>
                                <div>
                                    <a class="py-1 px-2 bg-red-400 rounded-3xl text-white" href="#" @click.prevent="commentCancelReply">cancel</a>
                                </div>
                            </div>
                        </transition>
                        <textarea-input v-model="commentForm.text" :error="commentErrors.text" id="text" label="Comment" />
                        <button-primary type="submit">Store</button-primary>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    import { eventBus } from '@/app'

    import TextInput from '@/components/TextInput'
    import TextareaInput from '@/components/TextareaInput'
    import ButtonPrimary from '@/components/ButtonPrimary'
    import Comment from '@/pages/Recipes/Comments/Comment'

    export default {
        name: 'Comments',
        components: {
            TextInput,
            TextareaInput,
            ButtonPrimary,
            Comment,
        },
        props :[
            'comments',
        ],
        data: () => ({
            treeComments: [],
            commentForm: {
                parent_id: null,
                text: null,
            },
            commentErrors: {
                text: null,
            },
            commentReplyTo: {}
        }),
        mounted() {
            this.treeComments = this.commentsTree(this.comments)
            eventBus.$on('commentReply', (comment) => {
                this.commentReply(comment)
            })
        },
        methods: {
            commentsTree(comments) {
                var tree = comments.filter(comment => {
                    comment.children = comments.filter(c => c.parent_id === comment.id);
                    return comment.parent_id === null;
                })

                return tree
            },
            commentStore() {
                if (!this.$store.getters.isAuthenticated) {
                    eventBus.$emit('openLoginModal')
                    return
                }
                axios.post('/api/recipes/' + this.$route.params.id + '/comment', this.commentForm)
                .then(res => {
                    console.log(res.data)
                })
            },
            commentScroll(ref) {
                console.log(this.$refs)

                var el = document.querySelectorAll('[data-comment-id="'+ref+'"]')[0]
                //var el = this.$refs[ref][0]
                
                console.log(el)

                el.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                })
                this.setAnimate(el)
            },
            setAnimate(el) {
                el.classList.add('animate-bounce')
                setTimeout(() => (el.classList.remove('animate-bounce')), 1000);
            },
            commentCancelReply() {
                this.commentForm.parent_id = null
                this.commentReplyTo = {}
            },
            commentReply(comment) {
                this.$refs.commentForm.scrollIntoView({
                    behavior: 'smooth'
                })
                if(this.commentForm.parent_id) {
                    this.commentForm.parent_id = null
                    this.commentReplyTo = {}
                    setTimeout(() => (this.commentReply(comment)), 300);
                } else {
                    this.commentForm.parent_id = comment.id
                    this.commentReplyTo = Object.assign({}, comment)

                    this.commentReplyTo.text = this.commentReplyTo.user_id + ': ' + this.commentReplyTo.text

                    if(this.commentReplyTo.text.length > 64) {
                        this.commentReplyTo.text = this.commentReplyTo.text.slice(0, 60)  + ' ...'
                    }
                }
            },
        },
    }
</script>
