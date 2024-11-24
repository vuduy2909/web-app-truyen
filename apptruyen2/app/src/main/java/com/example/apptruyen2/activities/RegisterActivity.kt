package com.example.apptruyen2.activities

import android.content.Intent
import android.os.Bundle
import android.util.Patterns
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.example.apptruyen2.api.RetrofitClient
import com.example.apptruyen2.data.auth.PostResponse
import com.example.apptruyen2.databinding.ActivityRegisterBinding
import com.example.apptruyen2.helper.SharedPreferencesHelper
import com.example.apptruyen2.helper.Util
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch

class RegisterActivity : AppCompatActivity() {
  private lateinit var binding: ActivityRegisterBinding
  private val gson = Gson()

  override fun onCreate(savedInstanceState: Bundle?) {
    super.onCreate(savedInstanceState)

//    kiểm tra xem có bật mạng không nè
    if (!Util.checkNetworkConnected(this)) return

    binding = ActivityRegisterBinding.inflate(layoutInflater)
    setContentView(binding.root)

    val button = binding.registerBtn

    button.setOnClickListener {
      registering()
    }
  }

  private fun validateForm(): Boolean {
    val name = binding.editTextName.text.toString()
    val email = binding.editTextEmail.text.toString()
    val pass = binding.editTextPassword.text.toString()
    val rePass = binding.editTextRePassword.text.toString()

    if (name == "") {
      Toast.makeText(this, "Tên không được để trống", Toast.LENGTH_SHORT).show()
      return false
    }
    if (email == "") {
      Toast.makeText(this, "Email không được để trống", Toast.LENGTH_SHORT).show()
      return false
    }
    if (!Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
      Toast.makeText(this, "Email không đúng định dạng", Toast.LENGTH_SHORT).show()
      return false
    }
    if (pass == "") {
      Toast.makeText(this, "Mật khẩu không được để trống", Toast.LENGTH_SHORT).show()
      return false
    }
    if (rePass == "") {
      Toast.makeText(
        this,
        "Mật khẩu nhập lại không được để trống",
        Toast.LENGTH_SHORT
      ).show()
      return false
    }
    if (pass != rePass) {
      Toast.makeText(
        this,
        "Mật khẩu và mật khẩu nhập lại không khớp",
        Toast.LENGTH_SHORT
      ).show()
      return false
    }
    return true
  }

  private fun registering() {
    if (!validateForm()) return

    val name = binding.editTextName.text.toString()
    val email = binding.editTextEmail.text.toString()
    val pass = binding.editTextPassword.text.toString()

    val storyApi = RetrofitClient.create()
    CoroutineScope(Dispatchers.IO).launch {
      try {
        val response = storyApi.register(name, email, pass)

        val registerResponse = gson.fromJson<PostResponse>(
          response.string(),
          object : TypeToken<PostResponse>() {}.type
        )

        if (!registerResponse.status) {
          runOnUiThread {
            Toast.makeText(this@RegisterActivity, registerResponse.message, Toast.LENGTH_SHORT).show()
          }
          return@launch
        }
        // Tạo SharedPreferences trong Activity
        registerResponse.body?.let { SharedPreferencesHelper(this@RegisterActivity).saveUser(it) }
        startActivity(Intent(this@RegisterActivity, ProfileActivity::class.java))
      } catch (e: Exception) {
        println(e)
      }
    }
  }

}