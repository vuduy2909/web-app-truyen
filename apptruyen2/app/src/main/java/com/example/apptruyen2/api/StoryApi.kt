package com.example.apptruyen2.api


import okhttp3.MultipartBody
import okhttp3.ResponseBody
import retrofit2.http.*

interface StoryApi {
  @GET("/api/stories/list")
  suspend fun listStories(@Query("page") page: Int = 1): ResponseBody

  @GET("/api/stories/pin")
  suspend fun listStoriesPin(): ResponseBody

  @GET("/api/ranks/list-top-views")
  suspend fun listTopViews(@Query("page") page: Int = 1): ResponseBody

  @GET("/api/ranks/list-top-stars")
  suspend fun listTopStars(@Query("page") page: Int = 1): ResponseBody

  @GET("/api/stories/advanced-search?status=1")
  suspend fun listFull(@Query("page") page: Int = 1): ResponseBody

  @GET("/api/stories/show/{slug}")
  suspend fun showStory(@Path("slug") slug: String): ResponseBody

  //  Chapter
  @GET("/api/chapters/list/{slug}")
  suspend fun listChapters(
    @Path("slug") slug: String,
    @Query("sort") sort: String? = null
  ): ResponseBody

  @GET("/api/chapters/show/{slug}/chapter-{number}")
  suspend fun showChapter(@Path("slug") slug: String, @Path("number") number: Int): ResponseBody

  @GET("/api/chapters/download/{slug}")
  suspend fun downChapter(
    @Path("slug") slug: String,
    @Query("page") page: Int = 1,
    @Query("perPage") perPage: Int = 50,
  ): ResponseBody

  //  Category
  @GET("/api/categories/list")
  suspend fun listCategories(): ResponseBody

  @GET("/api/categories/show/{slug}")
  suspend fun listStoryByCategory(
    @Path("slug") slug: String,
    @Query("page") page: Int = 1
  ): ResponseBody

  @GET("/api/stories/list")
  suspend fun searchStories(@Query("q") query: String, @Query("page") page: Int = 1): ResponseBody

  //      login
  @FormUrlEncoded
  @POST("/api/auth/login")
  suspend fun login(
    @Field("email") email: String,
    @Field("password") password: String
  ): ResponseBody

  //      register
  @FormUrlEncoded
  @POST("/api/auth/register")
  suspend fun register(
    @Field("name") name: String,
    @Field("email") email: String,
    @Field("password") password: String
  ): ResponseBody

  //      change info
  @FormUrlEncoded
  @POST("/api/auth/change-info")
  suspend fun changeInfo(
    @Field("name") name: String,
    @Field("email") email: String,
    @Field("gender") gender: Int
  ): ResponseBody

  //      change info
  @FormUrlEncoded
  @POST("/api/auth/change-password")
  suspend fun changePassword(
    @Field("old_password") oldPassword: String,
    @Field("new_password") newPassword: String,
  ): ResponseBody

  @Multipart
  @POST("/api/auth/change-avatar")
  suspend fun changeAvatar(
    @Part avatar: MultipartBody.Part
  ): ResponseBody
  //      history
  @GET("/api/histories/list")
  suspend fun listHistories(): ResponseBody

  @GET("/api/histories/show/{slug}")
  suspend fun showHistory(@Path("slug") slug: String): ResponseBody

  @POST("/api/histories/destroy/{slug}")
  suspend fun destroyHistory(@Path("slug") slug: String): ResponseBody

  //      star
  @GET("/api/ranks/show-stars/{slug}")
  suspend fun showStarsByStory(@Path("slug") slug: String): ResponseBody

  @FormUrlEncoded
  @POST("/api/ranks/create-stars/{slug}")
  suspend fun createStar(
    @Path("slug") slug: String,
    @Field("stars") stars: Int
  ): ResponseBody

  //      Comments
  @GET("/api/stories/{slug}/comments")
  suspend fun listCommentsByStory(@Path("slug") slug: String): ResponseBody

  @FormUrlEncoded
  @POST("/api/stories/{slug}/comments")
  suspend fun createComment(
    @Path("slug") slug: String,
    @Field("content") content: String,
    @Field("parent") parent: Int?
  ): ResponseBody

  @POST("/api/comments/delete/{id}")
  suspend fun deleteComment(
    @Path("id") id: Int
  ): ResponseBody

  @FormUrlEncoded
  @POST("/api/comments/update/{id}")
  suspend fun updateComment(
    @Path("id") id: Int,
    @Field("content") content: String
  ): ResponseBody
}