package com.example.apptruyen2.fragment

//noinspection SuspiciousImport
import android.R
import android.content.Intent
import android.os.Bundle
import android.util.Patterns
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.AdapterView
import android.widget.ArrayAdapter
import android.widget.Toast
import androidx.fragment.app.Fragment
import com.example.apptruyen2.activities.ProfileActivity
import com.example.apptruyen2.api.RetrofitClient
import com.example.apptruyen2.constant.UserGenderEnum
import com.example.apptruyen2.data.auth.PostResponse
import com.example.apptruyen2.databinding.FragmentEditProfileBinding
import com.example.apptruyen2.helper.SharedPreferencesHelper
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext


class EditProfileFragment : Fragment() {
  private val gson = Gson()
  private lateinit var binding: FragmentEditProfileBinding
  private var genderEnum = UserGenderEnum.SECRET

  override fun onCreateView(
    inflater: LayoutInflater, container: ViewGroup?,
    savedInstanceState: Bundle?
  ): View {
    binding = FragmentEditProfileBinding.inflate(inflater, container, false)
    val view = binding.root

    val user = SharedPreferencesHelper(requireContext()).getUser()
    if (user == null) {
      requireActivity().finish()
      return view
    }

    val options = arrayOf("Nam", "Nữ", "LGBT", "Bí mật")

    binding.spGender.adapter =
      ArrayAdapter(requireContext(), R.layout.simple_spinner_dropdown_item, options)

    binding.spGender.onItemSelectedListener = object : AdapterView.OnItemSelectedListener {
      override fun onItemSelected(parent: AdapterView<*>?, view: View?, position: Int, id: Long) {
        genderEnum = UserGenderEnum.fromValue(options[position])
      }

      override fun onNothingSelected(parent: AdapterView<*>?) {
        TODO("Not yet implemented")
      }

    }
    binding.submitEditBtn.setOnClickListener {
      onSubmitEditInfo()
    }

    binding.editTextName.setText(user.name)
    binding.editTextEmail.setText(user.email)
    binding.spGender.setSelection(options.indexOf(user.gender))

    return view
  }

  private fun onSubmitEditInfo() {
    val user = SharedPreferencesHelper(requireContext()).getUser() ?: return
    if (!validateForm()) return

    val api = RetrofitClient.create(user.token)
    CoroutineScope(Dispatchers.IO).launch {
      try {
        val name = binding.editTextName.text.toString()
        val email = binding.editTextEmail.text.toString()

        val response = api.changeInfo(name, email, genderEnum.value)

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
          Toast.makeText(requireContext(), "Sửa thông tin thành công", Toast.LENGTH_SHORT)
            .show()
        }
        startActivity(Intent(requireContext(), ProfileActivity::class.java))
      } catch (e: Exception) {
        println(e)
      }
    }
  }

  private fun validateForm(): Boolean {
    val name = binding.editTextName.text.toString()
    val email = binding.editTextEmail.text.toString()

    if (name == "") {
      Toast.makeText(requireContext(), "Tên không được để trống", Toast.LENGTH_SHORT).show()
      return false
    }
    if (email == "") {
      Toast.makeText(requireContext(), "Email không được để trống", Toast.LENGTH_SHORT).show()
      return false
    }
    if (!Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
      Toast.makeText(requireContext(), "Email không đúng định dạng", Toast.LENGTH_SHORT).show()
      return false
    }
    return true
  }
}