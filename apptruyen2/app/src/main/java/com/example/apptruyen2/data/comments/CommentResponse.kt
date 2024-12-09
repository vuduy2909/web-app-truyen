package com.example.apptruyen2.data.comments

data class CommentResponse(
  val status: Boolean,
  val message: String,
  val body: ArrayList<Comment>?
)
