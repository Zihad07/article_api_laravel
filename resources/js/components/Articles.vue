<template>
    <div class="articles">

        <div class="card card-body my-4 p-2">
            <form @submit.prevent="articleOperation">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text"  id="title" class="form-control" v-model.trim="article.title">
                </div>
                <div class="form-group">
                    <label for="body">Content</label>
                    <textarea  id="body"  class="form-control" cols="30" rows="3" v-model.trim="article.body"></textarea>
                </div>

                <div v-if="feedback" class="alert alert-danger" role="alert">
                    <strong>{{feedback}}</strong>
                </div>

                <span class="form-group">
                    <button class="btn btn-outline-info btn-sm" @click.prevent="cleanCode">Reset</button>
                </span>

                <span class="form-group">
                    <button class="btn btn-sm btn-outline-success float-right" type="submit">Save</button>
                </span>
            </form>
        </div>

        <h2>Articles</h2>

        <div v-if="success" class="alert alert-success" role="alert">
            <strong>{{success}}</strong>
        </div>

        <div class="pagination">
            <nav aria-label="">
                <ul class="pagination">
                    <li class="page-item" :class="{'disabled':!pagination.prev_page_url}">
                        <span class="page-link"  @click="fetchArticles(pagination.prev_page_url)">Prev</span>
                    </li>
                    <li class="page-item disabled">
                        <span class="page-link">Page {{pagination.current_page}} of {{pagination.last_page}}</span>
                    </li>
                    <li class="page-item" :class="{'disabled':!pagination.next_page_url}">
                        <span class="page-link"  @click="fetchArticles(pagination.next_page_url)">Next</span>
                    </li>


                </ul>
            </nav>

            <div class="">
                <span  class="btn btn-primary ml-2">
                    Articles <span class="badge badge-pill badge-light">{{pagination.total_article ? pagination.total_article:'0'}}</span>
                </span>
            </div>
        </div>
        <div class="card card-body mb-2" v-for="eachArticle in articles" :key="eachArticle.id">
            <h3>{{eachArticle.title}}</h3>
            <p>{{eachArticle.body}}</p>

            <div class="card-footer">
                <div class="float-right">
                    <button class="btn btn-outline-success btn-sm" @click.prevent="editArticleInForm(eachArticle)">Edit</button>
                    <button class="btn btn-outline-danger btn-sm" @click.prevent="deleteArticle(eachArticle.id) ">Delete</button>

                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import axios from 'axios'
    export default {
        name:'Articles',
        data() {
          return {
              articles : [],
              article: {
                  id:null,
                  title:null,
                  body:null
              },
              article_id:null,
              pagination: {},
              edit: false,
              feedback : null,
              success : null
          }
        },
        mounted() {
           this.fetchArticles()
        },
        methods : {
            cleanCode(){
              this.article.id = null
              this.article.title = null
              this.article.body = null
              this.edit = false
              this.feedback = null
            },
            fetchArticles(page_url){
                let page = page_url || '/articles'
                axios.get(page).then(response=>{
                    // console.log(response.data)
                    // response.data.forEach(art=>{this.articles.push(art)})
                    let res = response.data
                    this.articles = res.data
                    // console.log(this.articles)
                    this.makePagination(res.links,res.meta)
                }).catch(err=>{
                    alert(err.message)
                })
            },
            makePagination(links, meta){
                let myPagination = {
                    'first_page_url':links.first,
                    'next_page_url':links.next,
                    'prev_page_url':links.prev,
                    'last_page_url':links.last,
                    'current_page':meta.current_page,
                    'last_page': meta.last_page,
                    'total_article':meta.total
                };
                // console.log(myPagination)

            //    assign pagination
                this.pagination = myPagination
            },
            deleteArticle(id){
                if(confirm("Are you sure you want delete article")){
                    axios.delete(`article/${id}/delete`)
                    .then(()=>{
                        let page = `http://articleapi.test/articles?page=${this.pagination.current_page}`
                        this.fetchArticles(page)
                    })
                    .catch(err=>{
                        alert(err.message)
                    })
                }
            },
            articleOperation() {
                if(!this.article.title  || !this.article.body){
                    this.feedback = "You Must Be Fill All Field"
                    return
                }
                if(this.edit !== true){
                    this.addArticle()
                }else {
                    this.editArticle()
                }
            },
            addArticle() {
                // alert('add article')
                axios.post(`/article`,this.article)
                .then(()=>{
                    this.fetchArticles()
                    this.cleanCode()
                    this.successMessage('One New Article Added Succesfully')
                })
                .catch(err=>{
                    alert(err.message)
                })

            },
            editArticle() {
                // alert('edit article')
                let link = `article/${this.article.id}/edit`
                axios.put(link,this.article)
                    .then(()=>{
                        let page = `http://articleapi.test/articles?page=${this.pagination.current_page}`
                        this.fetchArticles(page)
                        this.cleanCode()
                        this.successMessage('Update Article succesfully')
                    })
                    .catch(err=>{
                        alert(err.message)
                    })
            },
            editArticleInForm(eachArticle) {
                this.edit = true
                this.article.id = eachArticle.id
                this.article.title = eachArticle.title
                this.article.body = eachArticle.body
            },
            successMessage(msg) {
                this.success = msg
                setTimeout(()=>{
                    this.success = null
                },4000)
            }
        }
    }
</script>


<style scoped>
    .page-link {
        cursor: pointer;
    }
</style>
