package com.example.apptruyen2.fragment

import android.annotation.SuppressLint
import android.app.Activity.RESULT_OK
import android.content.Context
import android.content.Intent
import android.graphics.Bitmap
import android.graphics.BitmapFactory
import android.net.Uri
import android.os.Bundle
import android.provider.MediaStore
import android.view.*
import android.widget.Toast
import androidx.activity.result.ActivityResult
import androidx.activity.result.contract.ActivityResultContracts
import androidx.fragment.app.Fragment
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.bumptech.glide.Glide
import com.example.apptruyen2.R
import com.example.apptruyen2.activities.*
import com.example.apptruyen2.adapter.ListHistoryAdapter
import com.example.apptruyen2.api.RetrofitClient
import com.example.apptruyen2.data.auth.ChangeAvatarResponse
import com.example.apptruyen2.data.auth.User
import com.example.apptruyen2.data.history.DestroyHistoryResponse
import com.example.apptruyen2.data.history.HistoriesResponse
import com.example.apptruyen2.data.history.History
import com.example.apptruyen2.databinding.FragmentProfileBinding
import com.example.apptruyen2.helper.SharedPreferencesHelper
import com.example.apptruyen2.view.OnItemHistoryClickListener
import com.example.apptruyen2.view.OnItemMenuProfileClickListener
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext
import okhttp3.MediaType.Companion.toMediaTypeOrNull
import okhttp3.MultipartBody
import okhttp3.RequestBody.Companion.toRequestBody
import java.io.ByteArrayOutputStream
import java.io.InputStream


class ProfileFragment(
  private val onItemMenuProfileClickListener: OnItemMenuProfileClickListener
) : Fragment() {
  private lateinit var binding: FragmentProfileBinding
  private val gson = Gson()
  private lateinit var adapter: ListHistoryAdapter
  private lateinit var recyclerView: RecyclerView

  override fun onCreateView(
    inflater: LayoutInflater, container: ViewGroup?,
    savedInstanceState: Bundle?
  ): View {
    binding = FragmentProfileBinding.inflate(inflater, container, false)
    val view = binding.root
    loadInfoUser()
    loadHistory()
    setEvenItemMenu()
    recyclerView = binding.recyclerView
    recyclerView.layoutManager = LinearLayoutManager(requireContext())
    //  click item trong recyclerView
    adapter = ListHistoryAdapter(requireContext(), object : OnItemHistoryClickListener {
      override fun onItemHistoryClick(history: History) {
        val intent = Intent(requireContext(), ChapterActivity::class.java)
        intent.putExtra("slug", history.slug)

        println("number: ${history.chapterNumber}")
        intent.putExtra("number", history.chapterNumber)
        startActivity(intent)
      }

      override fun onBtnDeleteItemHistoryClick(slug: String) {
        val user = SharedPreferencesHelper(requireContext()).getUser()
        if (user === null) return

        val api = RetrofitClient.create(user.token)

        CoroutineScope(Dispatchers.IO).launch {
          val response = api.destroyHistory(slug)

          val destroyHistoryResponse = Gson().fromJson<DestroyHistoryResponse>(
            response.string(),
            object : TypeToken<DestroyHistoryResponse>() {}.type
          )

          withContext(Dispatchers.Main) {
            Toast.makeText(context, destroyHistoryResponse.message, Toast.LENGTH_SHORT).show()
          }

          if (!destroyHistoryResponse.status) return@launch

          loadHistory()
        }
      }
    })

    recyclerView.adapter = adapter

    binding.imgAvatar.setOnLongClickListener {
      val intent = Intent(Intent.ACTION_PICK, MediaStore.Images.Media.EXTERNAL_CONTENT_URI)
      intent.addFlags(Intent.FLAG_GRANT_READ_URI_PERMISSION)
      pickImage.launch(intent)

      true
    }

    return view
  }

  @SuppressLint("SetTextI18n")
  private fun loadInfoUser() {
    // Tạo SharedPreferences trong Activity
    val sharedPref = requireContext().getSharedPreferences("user_local", Context.MODE_PRIVATE)
    val userJson = sharedPref.getString("user_json", null);
    if (userJson === null) {
      return
    }
    val user = Gson().fromJson(userJson, User::class.java)
    Glide.with(this).load(user.avatar).into(binding.imgAvatar)
    binding.tvName.text = user.name
    binding.tvLevel.text = "Cấp độ: ${user.level}"
    binding.tvEmail.text = "Email: " + user.email
    binding.tvGender.text = "Giới tính: " + user.gender
  }

  private fun loadHistory() {
    // Tạo SharedPreferences trong Activity
    val user = SharedPreferencesHelper(requireContext()).getUser()
    if (user === null) return

    val api = RetrofitClient.create(user.token)

    CoroutineScope(Dispatchers.IO).launch {
      try {
        val response = api.listHistories()

        val historiesResponse = gson.fromJson<HistoriesResponse>(
          response.string(),
          object : TypeToken<HistoriesResponse>() {}.type
        )

        if (!historiesResponse.status) {
          requireActivity().runOnUiThread {
            Toast.makeText(requireContext(), historiesResponse.message, Toast.LENGTH_SHORT).show()
          }
          return@launch
        }

        withContext(Dispatchers.Main) {
          adapter.allHistory(historiesResponse.body)
        }
      } catch (e: Throwable) {
        println(e)
      }
    }
  }

  private fun setEvenItemMenu() {
    binding.navProfile.setOnMenuItemClickListener {
      when (it.itemId) {
        R.id.logout_btn -> {
          SharedPreferencesHelper(requireContext()).clearUser()
          startActivity(Intent(requireContext(), ProfileActivity::class.java))
          true
        }

        R.id.edit_btn -> {
          onItemMenuProfileClickListener.onItemEditClick()
          true
        }

        R.id.password_btn -> {
          onItemMenuProfileClickListener.onItemChangePasswordClick()
          true
        }
        else -> false
      }
    }
  }

  private val pickImage = registerForActivityResult<Intent, ActivityResult>(
    ActivityResultContracts.StartActivityForResult()
  ) { result: ActivityResult ->
    if (result.resultCode == RESULT_OK) {
      if (result.data != null) {
        val imageUri: Uri? = result.data!!.data
        try {
          val inputStream: InputStream? = imageUri?.let {
            requireContext().contentResolver.openInputStream(
              it
            )
          }
          val bitmap = BitmapFactory.decodeStream(inputStream)
          binding.imgAvatar.setImageBitmap(bitmap)

          submitChangeAvatar(bitmap)

        } catch (e: Throwable) {
          println("/------CÓ LỖI----------/\n $e\n/------CÓ LỖI------------/")
        }
      }
    }
  }

  private fun submitChangeAvatar(bitmap: Bitmap) {
    try {
      val byteArrayOutputStream = ByteArrayOutputStream()
      bitmap.compress(Bitmap.CompressFormat.PNG, 100, byteArrayOutputStream)
      val byteArray = byteArrayOutputStream.toByteArray()
      val requestFile =
        byteArray.toRequestBody("image/png".toMediaTypeOrNull())
      val body = MultipartBody.Part.createFormData("avatar", "image.png", requestFile)

      val user =
        SharedPreferencesHelper(requireContext()).getUser() ?: return
      val api = RetrofitClient.create(user.token)

      CoroutineScope(Dispatchers.IO).launch {
        val response = api.changeAvatar(body)

        val postResponse = gson.fromJson(response.string(), ChangeAvatarResponse::class.java)
        requireActivity().runOnUiThread {
          if (!postResponse.status) {
            Toast.makeText(requireContext(), postResponse.message, Toast.LENGTH_SHORT).show()
            return@runOnUiThread
          }
          user.avatar = postResponse.body.toString()
          SharedPreferencesHelper(requireContext()).clearUser()
          SharedPreferencesHelper(requireContext()).saveUser(user)

          Toast.makeText(requireContext(), "Đổi ảnh thành công", Toast.LENGTH_SHORT).show()
        }
      }
    } catch (e: Throwable) {
      println("/------CÓ LỖI----------/\n $e\n/------CÓ LỖI------------/")
    }
  }
}