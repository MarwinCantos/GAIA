function sendOtp() {
    const email = document.getElementById('email').value;

    if (!email) {
        alert('Please enter your email.');
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'generate_otp.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status === 200) {
            alert(xhr.responseText);

            // Hide the email input, Send OTP button, and the header text
            document.querySelector('.text-reset').style.display = 'none';
            document.querySelector('.input-group').style.display = 'none';
            document.getElementById('generateOtp').style.display = 'none';
            document.querySelector('p[style]').style.display = 'none';
            document.querySelectorAll('p')[1].style.display = 'none'; // Hides the second paragraph

            // Show the OTP form
            const otpForm = document.getElementById('otpForm');
            otpForm.style.display = 'block';
        } else {
            alert('Failed to generate OTP. Please try again.');
        }
    };

    xhr.send('email=' + encodeURIComponent(email));
}

function verifyOtp() {
    const otpInput = document.getElementById("otpInput");
    const otpMessage = document.getElementById("otpMessage");
    const otp = otpInput.value.trim();
  
    // Clear previous error messages
    otpMessage.textContent = "";
  
    if (!otp || otp.length !== 6 || !/^\d{6}$/.test(otp)) {
      otpMessage.textContent = "Please enter a valid 6-digit OTP.";
      otpMessage.style.color = "red";
      return;
    }
  
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "verify_otp.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  
    xhr.onload = function () {
      if (xhr.status === 200) {
        if (xhr.responseText.includes("success")) {
          otpInput.value = ""; 
          // Show the password reset modal
          document.getElementById("passwordModal").style.display = "flex";
        } else {
          otpMessage.textContent = xhr.responseText;
          otpMessage.style.color = "red";
        }
      } else {
        otpMessage.textContent = "An error occurred while verifying OTP.";
        otpMessage.style.color = "red";
      }
    };
  
    xhr.onerror = function () {
      otpMessage.textContent = "Network error. Please try again.";
      otpMessage.style.color = "red";
    };
  
    xhr.send("otp=" + encodeURIComponent(otp));
  }
  
  
  function closeModal() {
    // Clear previous error messages
    passwordMessage.textContent = "";
    document.getElementById("newPassword").value = "";
    document.getElementById("confirmPassword").value = "";

    document.getElementById("passwordModal").style.display = "none";
  }
  
  function resetPassword() {
    const newPassword = document.getElementById("newPassword").value.trim();
    const confirmPassword = document.getElementById("confirmPassword").value.trim();
    const passwordMessage = document.getElementById("passwordMessage");
  
    // Clear previous messages
    passwordMessage.textContent = "";
  
    // Validations
    if (!newPassword || !confirmPassword) {
      passwordMessage.textContent = "All fields are required.";
      return;
    }
  
    if (newPassword !== confirmPassword) {
      passwordMessage.textContent = "Passwords do not match.";
      return;
    }
  
    if (newPassword.length < 8) {
      passwordMessage.textContent = "Password must be at least 8 characters.";
      return;
    }
  
    // Send the request using XMLHttpRequest
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "reset_password.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  
    xhr.onload = function () {
      if (xhr.status === 200) {
        if (xhr.responseText.includes("success")) {
          alert("Password successfully updated.");
          closeModal();
          resetOtp();
          window.location.href = '../login.php';
        } else {
          passwordMessage.textContent = xhr.responseText;
        }
      } else {
        passwordMessage.textContent = "An error occurred. Please try again.";
      }
    };
  
    xhr.onerror = function () {
      passwordMessage.textContent = "Network error. Please check your connection and try again.";
    };
  
    xhr.send(`newPassword=${encodeURIComponent(newPassword)}&confirmPassword=${encodeURIComponent(confirmPassword)}`);
  }
  
  function resetOtp() {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "reset_otp.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  
    xhr.onload = function () {
      if (xhr.status === 200) {
        if (xhr.responseText.includes("success")) {
          console.log("OTP has been reset to 0.");
        } else {
          console.error("Failed to reset OTP: " + xhr.responseText);
        }
      }
    };
  
    xhr.onerror = function () {
      console.error("Network error while resetting OTP.");
    };
  
    xhr.send();
  }

  function togglePassword(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const eyeIcon = document.getElementById(iconId);

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}
