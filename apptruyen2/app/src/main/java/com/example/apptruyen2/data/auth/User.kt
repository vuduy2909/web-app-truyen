package com.example.apptruyen2.data.auth

data class User(
  val id: Int,
  val name: String,
  val email: String,
  val gender: String,
  val level: String,
  var avatar: String,
  val token: String,
  val password: String,
)
