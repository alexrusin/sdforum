<template>
    <div>
       <div v-for="(reply, index) in items">
            <reply :data="reply" @deleted="remove(index)" :key="reply.id"></reply>
       </div>

       <paginator :data-set="dataSet" @changed="fetch"></paginator>

       <p v-if="$parent.locked">
            This thread has been locked.  No more replies are allowed.
       </p>

       <new-reply @created="add" v-else></new-reply>
    </div>
</template>

<script>
    import Reply from './ReplyComponent.vue';
    import NewReply from './NewReplyComponent.vue';
    import collection from '../mixins/collection';
    import {array_merge} from '../lib.js';
    export default {

        components: {Reply, NewReply},
        mixins: [collection],
       
        data() {
          return {
            dataSet: false
           }
        },

        created() {
            this.fetch();

            // let endArray = array_merge({ bgColor: 3, textColor: 4 }, {'bgColor':5});

            // console.log(endArray);

        },

        methods: {
            fetch(page) {
                axios.get(this.url(page)).then(this.refresh);
            },

            url(page) {
                if (!page) {

                    let query = location.search.match(/page=(\d+)/);

                    page = query ? query[1] : 1;
                }

                return `${location.pathname}/replies?page=${page}`;
            },

            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;
                window.scrollTo(0, 0);
            }            
        }
    }
</script>