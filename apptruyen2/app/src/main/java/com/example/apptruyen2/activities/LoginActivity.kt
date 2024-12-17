package com.example.apptruyen2.activities

import android.annotation.SuppressLint
import android.content.Intent
import android.os.Bundle
import android.util.Patterns
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.example.apptruyen2.api.RetrofitClient
import com.example.apptruyen2.data.auth.PostResponse
import com.example.apptruyen2.databinding.ActivityLoginBinding
import com.example.apptruyen2.helper.SharedPreferencesHelper
import com.example.apptruyen2.helper.Util
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch

class LoginActivity : AppCompatActivity() {
  private lateinit var binding: ActivityLoginBinding
  private val gson = Gson()

  override fun onCreate(savedInstanceState: Bundle?) {
    super.onCreate(savedInstanceState)

//    kiểm tra xem có bật mạng không nè
    if (!Util.checkNetworkConnected(this)) return

    binding = ActivityLoginBinding.inflate(layoutInflater)
    setContentView(binding.root)

    val email = binding.editTextEmail
    val password = binding.editTextPassword
    val button = binding.buttonLogin

    button.setOnClickListener {
      login(email.text.toString(), password.text.toString())
    }

    binding.redirectRegister.setOnClickListener {
      val intent = Intent(this, RegisterActivity::class.java)
      startActivity(intent)
    }
  }

  private fun validateForm(email: String, password: String): Boolean {
    if (email == "") {
      Toast.makeText(this, "Email không được để trống", Toast.LENGTH_SHORT).show()
      return false
    }
    if (!Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
      Toast.makeText(this, "Email không đúng định dạng", Toast.LENGTH_SHORT).show()
      return false
    }
    if (password == "") {
      Toast.makeText(this, "Mật khẩu không được để trống", Toast.LENGTH_SHORT).show()
      return false
    }
    return true
  }
  @SuppressLint("CommitPrefEdits")
  private fun login(email: String, password: String) {

    if (!validateForm(email, password)) return

    val storyApi = RetrofitClient.create()
    CoroutineScope(Dispatchers.IO).launch {
      try {
        val response = storyApi.login(email, password)

        val loginResponse = gson.fromJson<PostResponse>(
          response.string(),
          object : TypeToken<PostResponse>() {}.type
        )

        if (!loginResponse.status) {
          runOnUiThread {
            Toast.makeText(this@LoginActivity, loginResponse.message, Toast.LENGTH_SHORT).show()
          }
          return@launch
        }
        // Tạo SharedPreferences trong Activity
        loginResponse.body?.let { SharedPreferencesHelper(this@LoginActivity).saveUser(it) }
        startActivity(Intent(this@LoginActivity, ProfileActivity::class.java))
      } catch (e: Exception) {
        println(e)
      }
    }
  }
}