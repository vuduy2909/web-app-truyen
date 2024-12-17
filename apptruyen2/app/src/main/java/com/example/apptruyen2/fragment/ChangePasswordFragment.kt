package com.example.apptruyen2.fragment

import android.content.Intent
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import androidx.fragment.app.Fragment
import com.example.apptruyen2.activities.ProfileActivity
import com.example.apptruyen2.api.RetrofitClient
import com.example.apptruyen2.data.auth.PostResponse
import com.example.apptruyen2.databinding.FragmentChangePasswordBinding
import com.example.apptruyen2.helper.SharedPreferencesHelper
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext

class ChangePasswordFragment : Fragment() {
  private val gson = Gson()
  private lateinit var binding: FragmentChangePasswordBinding

  override fun onCreateView(
    inflater: LayoutInflater, container: ViewGroup?,
    savedInstanceState: Bundle?
  ): View {
    binding = FragmentChangePasswordBinding.inflate(inflater, container, false)
    val view = binding.root

    binding.submitChangePassBtn.setOnClickListener {
      onSubmitChangePassword()
    }

    return view
  }

  private fun onSubmitChangePassword() {
    val user = SharedPreferencesHelper(requireContext()).getUser() ?: return
    if (!validateForm()) return

    val api = RetrofitClient.create(user.token)
    CoroutineScope(Dispatchers.IO).launch {
      try {
        val oldPass = binding.editTextOldPass.text.toString()
        val newPass = binding.editTextPassword.text.toString()

        val response = api.changePassword(oldPass, newPass)

        val changeInfoResponse = gson.fromJson<PostResponse>(
          response.string(),
          object : TypeToken<PostResponse>() {}.type
        )

        if (!changeInfoResponse.status) {
          withContext(Dispatchers.Main) {
            Toast.makeText(requireContext(), changeInfoResponse.message, Toast.LENGTH_SHORT)
              .show()

          }
          return@launch
        }
        // Tạo SharedPreferences trong Activity
        changeInfoResponse.body?.let { SharedPreferencesHelper(requireContext()).saveUser(it) }

        withContext(Dispatchers.Main) {
          Toast.makeText(requireContext(), "Sửa mật khẩu thành công", Toast.LENGTH_SHORT)
            .show()
        }
        startActivity(Intent(requireContext(), ProfileActivity::class.java))
      } catch (e: Exception) {
        println(e)
      }
    }
  }

  private fun validateForm(): Boolean {
    val oldPass = binding.editTextOldPass.text.toString()
    val newPass = binding.editTextPassword.text.toString()
    val confirmPass = binding.editTextRePassword.text.toString()

    if (oldPass == "") {
      Toast.makeText(requireContext(), "Mật khẩu cũ không được để trống", Toast.LENGTH_SHORT).show()
      return false
    }
    if (newPass == "") {
      Toast.makeText(requireContext(), "Mật khẩu mới không được để trống", Toast.LENGTH_SHORT).show()
      return false
    }
    if (confirmPass == "") {
      Toast.makeText(requireContext(), "Mật khẩu nhập lại không được để trống", Toast.LENGTH_SHORT).show()
      return false
    }
    if (newPass != confirmPass) {
      Toast.makeText(requireContext(), "Mật khẩu nhập lại không khớp", Toast.LENGTH_SHORT).show()
      return false
    }
    return true
  }

}