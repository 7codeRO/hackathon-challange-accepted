<template>
    <div class="list-group-item list-group-item-action flex-column align-items-start">
        <div class="card" v-bind:class="{'bg-success': comment.isAnswer}">
            <div class="card-header">
                <div class="d-flex w-100 ">
                    <span class="name">{{ comment.user.full_name}}</span>
                    <small class="time">{{ comment.created_at }}</small>
                </div>
            </div>
            <div class="card-body">
                <p class="mb-1 content-text" v-html="comment.content"></p>
            </div>
            <div class="card-footer text-right">
                <div>
                    <button type="button" class="btn  btn-sm btn-success" @click="like($event); return false;">
                        <i class="fa fa-thumbs-up"></i> &nbsp;
                        <span class="badge badge-light">{{comment.likes}}</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" @click="dislike($event); return false;">
                        <i class="fa fa-thumbs-down"></i> &nbsp;
                        <span class="badge badge-light">{{comment.dislikes}}</span>
                    </button>
                    <button v-if="isTeacher" type="button" class="btn btn-sm btn-primary" v-bind:class="{ 'btn-success': comment.isAnswer }">
                        <i v-bind:class="{ answered: comment.isAnswer }" @click="answer()" title="Este raspunsul corect?" class="icon-check icons"></i>
                    </button>

                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import API from '../../api';
    export default {
        name: 'comment',
        props: ['comment', 'user'],
        created() {
            console.log(this.user.roles[0].name, 'user');
        },
        computed: {
            isTeacher() {
                return this.user.roles[0] && this.user.roles[0].name === 'teacher';
            }
        },
        methods: {
            async like() {
                await API.Comment.like(this.comment.id).then(res => {
                    if(res.data.status === 200) {
                        this.$notify({
                            group: 'foo',
                            type: 'success',
                            title: 'Notificare',
                            text: 'Like-ul a fost aprobat'
                        });
                        this.comment.likes++;
                    } else {
                        this.$notify({
                            group: 'foo',
                            type: 'error',
                            title: 'Notificare',
                            text: 'Nu mai puteti da like acestei postari'
                        });
                    }
                });

            },
            async dislike() {
                await API.Comment.dislike(this.comment.id).then(res => {
                    if(res.data.status === 200) {
                        this.$notify({
                            group: 'foo',
                            type: 'warning',
                            title: 'Notificare',
                            text: 'Disike-ul a fost aprobat'
                        });
                        this.comment.dislikes++;
                    } else {
                        this.$notify({
                            group: 'foo',
                            type: 'error',
                            title: 'Notificare',
                            text: 'Nu mai puteti da dislike acestei postari'
                        });
                    }
                });
            },
            async answer() {
                await API.Comment.answer(this.comment.id);
                this.comment.isAnswer = 1;
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Notificare',
                    text: 'Multumim pentru raspuns!'
                });
            }
        }
    }
</script>
<style lang="scss">
    .name{
        margin-right: 15px;
    }
    .answer {
        cursor: pointer;
    }

    .answered {
        color: #fff;
    }
    .content-text {
        font-size: 14px;
        line-height: 1.4;
    }
    .time {
        position: absolute;
        right: 10px;
        font-weight:600;
    }
</style>
