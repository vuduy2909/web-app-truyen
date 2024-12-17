package com.example.apptruyen2.data.auth

data class PostResponse(
  val status: Boolean,
  val message: String,
  val body: User?
)