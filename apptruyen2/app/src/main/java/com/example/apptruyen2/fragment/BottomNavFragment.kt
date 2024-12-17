package com.example.apptruyen2.fragment

import android.content.Intent
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.Fragment
import com.example.apptruyen2.R
import com.example.apptruyen2.activities.*
import com.example.apptruyen2.databinding.FragmentBottomNavBinding

class BottomNavFragment : Fragment() {
    private lateinit var binding: FragmentBottomNavBinding

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        binding = FragmentBottomNavBinding.inflate(inflater, container, false)
        val view = binding.root
        checkActivity()
        binding.bottomNavigationView.setOnItemSelectedListener {
            when (it.itemId) {
                R.id.home -> {
                    val intent = Intent(requireContext(), MainActivity::class.java)
                    startActivity(intent)
                    requireActivity().overridePendingTransition(0, 0)
                    true
                }
                R.id.category -> {
                    val intent = Intent(requireContext(), ListCategoryActivity::class.java)
                    startActivity(intent)
                    requireActivity().overridePendingTransition(0, 0)
                    true
                }
                R.id.search -> {
                    val intent = Intent(requireContext(), SearchStoryActivity::class.java)
                    startActivity(intent)
                    requireActivity().overridePendingTransition(0, 0)
                    true
                }
                R.id.local -> {
                    val intent = Intent(requireContext(), LocalStoryActivity::class.java)
                    startActivity(intent)
                    requireActivity().overridePendingTransition(0, 0)
                    true
                }
                R.id.profile -> {
                    val intent = Intent(requireContext(), ProfileActivity::class.java)
                    startActivity(intent)
                    requireActivity().overridePendingTransition(0, 0)
                    true
                }
                else -> false
            }
        }
        return view
    }

    private fun checkActivity() {
        when (requireActivity()) {
            is MainActivity -> {
                binding.bottomNavigationView.menu.findItem(R.id.home).isChecked = true
            }
            is ListCategoryActivity -> {
                binding.bottomNavigationView.menu.findItem(R.id.category).isChecked = true
            }
            is SearchStoryActivity -> {
                binding.bottomNavigationView.menu.findItem(R.id.search).isChecked = true
            }
            is LocalStoryActivity -> {
                binding.bottomNavigationView.menu.findItem(R.id.local).isChecked = true
            }
            is ProfileActivity -> {
                binding.bottomNavigationView.menu.findItem(R.id.profile).isChecked = true
            }
        }
    }
}

