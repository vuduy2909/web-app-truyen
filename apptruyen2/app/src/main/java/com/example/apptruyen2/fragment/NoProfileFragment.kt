package com.example.apptruyen2.fragment

import android.content.Intent
import android.os.Bundle
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import com.example.apptruyen2.R
import com.example.apptruyen2.activities.LoginActivity
import com.example.apptruyen2.activities.RegisterActivity
import com.example.apptruyen2.databinding.FragmentListStoryBinding
import com.example.apptruyen2.databinding.FragmentNoProfileBinding

class NoProfileFragment : Fragment() {
  private lateinit var binding: FragmentNoProfileBinding

  override fun onCreate(savedInstanceState: Bundle?) {
    super.onCreate(savedInstanceState)
  }

  override fun onCreateView(
    inflater: LayoutInflater, container: ViewGroup?,
    savedInstanceState: Bundle?
  ): View {
    binding = FragmentNoProfileBinding.inflate(inflater, container, false)
    val view = binding.root

    binding.btnLogin.setOnClickListener {
      startActivity(Intent(requireContext(), LoginActivity::class.java))
    }

    binding.btnRegister.setOnClickListener {
      startActivity(Intent(requireContext(), RegisterActivity::class.java))
    }
    return view
  }

}