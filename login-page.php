<?php
/*
Template Name: SMS Login
*/
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود با پیامک | جاگیم</title>
    <?php wp_head(); ?>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .logo {
            margin-bottom: 30px;
        }

        .logo h1 {
            color: #4f46e5;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .logo p {
            color: #6b7280;
            font-size: 1.1rem;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: right;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 500;
        }

        .input-group {
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f9fafb;
            text-align: center;
        }

        .input-group input:focus {
            outline: none;
            border-color: #4f46e5;
            background: white;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .submit-btn {
            width: 100%;
            background: #4f46e5;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background: #4338ca;
            transform: translateY(-2px);
        }

        .submit-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }

        .resend-btn {
            background: none;
            border: none;
            color: #4f46e5;
            cursor: pointer;
            margin-top: 10px;
            font-size: 14px;
        }

        .resend-btn:disabled {
            color: #9ca3af;
            cursor: not-allowed;
        }

        .timer {
            color: #6b7280;
            font-size: 14px;
            margin-top: 5px;
        }

        .message {
            padding: 12px;
            border-radius: 8px;
            margin-top: 15px;
            font-size: 14px;
            display: none;
        }

        .message.error {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .message.success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s ease-in-out infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .back-btn {
            background: #6b7280;
            margin-top: 10px;
        }

        .back-btn:hover {
            background: #4b5563;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="logo">
            <h1>جاگیم</h1>
            <p>ورود با پیامک</p>
        </div>

        <!-- مرحله ۱: دریافت شماره همراه -->
        <div class="step active" id="step-1">
            <div class="form-group">
                <label for="mobile">شماره همراه</label>
                <div class="input-group">
                    <input
                        type="tel"
                        id="mobile"
                        name="mobile"
                        placeholder="09xxxxxxxxx"
                        required
                        pattern="09[0-9]{9}"
                        maxlength="11">
                </div>
            </div>

            <button type="button" class="submit-btn" id="send-code-btn">
                <span id="send-btn-text">ارسال کد تأیید</span>
                <div class="loading" id="send-loading" style="display: none;"></div>
            </button>

            <div id="message" class="message"></div>
        </div>

        <!-- مرحله ۲: وارد کردن کد تأیید -->
        <div class="step" id="step-2">
            <div class="form-group">
                <label for="verification-code">کد تأیید</label>
                <div class="input-group">
                    <input
                        type="text"
                        id="verification-code"
                        name="verification-code"
                        placeholder="_ _ _ _ _ _"
                        required
                        pattern="[0-9]{6}"
                        maxlength="6">
                </div>
                <div class="timer" id="timer">02:00</div>
            </div>

            <button type="button" class="submit-btn" id="verify-btn">
                <span id="verify-btn-text">تأیید و ورود</span>
                <div class="loading" id="verify-loading" style="display: none;"></div>
            </button>

            <button type="button" class="resend-btn" id="resend-btn" disabled>
                ارسال مجدد کد
            </button>

            <button type="button" class="submit-btn back-btn" id="back-btn">
                بازگشت
            </button>

            <div id="verify-message" class="message"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const step1 = document.getElementById('step-1');
            const step2 = document.getElementById('step-2');
            const mobileInput = document.getElementById('mobile');
            const codeInput = document.getElementById('verification-code');
            const sendCodeBtn = document.getElementById('send-code-btn');
            const verifyBtn = document.getElementById('verify-btn');
            const resendBtn = document.getElementById('resend-btn');
            const backBtn = document.getElementById('back-btn');
            const timer = document.getElementById('timer');
            const message = document.getElementById('message');
            const verifyMessage = document.getElementById('verify-message');

            let countdown;
            let mobileNumber = '';

            // فرمت شماره همراه هنگام تایپ
            mobileInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.startsWith('98')) {
                    value = '0' + value.substring(2);
                }
                if (value.length > 11) {
                    value = value.substring(0, 11);
                }
                e.target.value = value;
            });

            // ارسال کد تأیید
            sendCodeBtn.addEventListener('click', function() {
                mobileNumber = mobileInput.value.trim();

                if (!mobileNumber || !/^09[0-9]{9}$/.test(mobileNumber)) {
                    showMessage('شماره همراه معتبر نیست', 'error', message);
                    return;
                }

                setLoading('send', true);

                const formData = new FormData();
                formData.append('action', 'send_verification_code');
                formData.append('security', '<?php echo wp_create_nonce("sms_verification_nonce"); ?>');
                formData.append('mobile', mobileNumber);

                fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showMessage(data.data, 'success', message);
                            // رفتن به مرحله بعد
                            step1.classList.remove('active');
                            step2.classList.add('active');
                            startTimer();
                            codeInput.focus();
                        } else {
                            showMessage(data.data, 'error', message);
                        }
                        setLoading('send', false);
                    })
                    .catch(error => {
                        showMessage('خطا در ارتباط با سرور', 'error', message);
                        setLoading('send', false);
                    });
            });

            // تأیید کد و ورود
            verifyBtn.addEventListener('click', function() {
                const code = codeInput.value.trim();

                if (!code || !/^[0-9]{6}$/.test(code)) {
                    showMessage('کد تأیید باید ۶ رقمی باشد', 'error', verifyMessage);
                    return;
                }

                setLoading('verify', true);

                const formData = new FormData();
                formData.append('action', 'verify_and_login');
                formData.append('security', '<?php echo wp_create_nonce("sms_verification_nonce"); ?>');
                formData.append('mobile', mobileNumber);
                formData.append('code', code);

                fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showMessage('ورود موفق! در حال هدایت...', 'success', verifyMessage);
                            setTimeout(() => {
                                window.location.href = data.data.redirect;
                            }, 1000);
                        } else {
                            showMessage(data.data, 'error', verifyMessage);
                        }
                        setLoading('verify', false);
                    })
                    .catch(error => {
                        showMessage('خطا در ارتباط با سرور', 'error', verifyMessage);
                        setLoading('verify', false);
                    });
            });

            // ارسال مجدد کد
            resendBtn.addEventListener('click', function() {
                setLoading('send', true);

                const formData = new FormData();
                formData.append('action', 'send_verification_code');
                formData.append('security', '<?php echo wp_create_nonce("sms_verification_nonce"); ?>');
                formData.append('mobile', mobileNumber);

                fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showMessage('کد جدید ارسال شد', 'success', verifyMessage);
                            startTimer();
                        } else {
                            showMessage(data.data, 'error', verifyMessage);
                        }
                        setLoading('send', false);
                    })
                    .catch(error => {
                        showMessage('خطا در ارتباط با سرور', 'error', verifyMessage);
                        setLoading('send', false);
                    });
            });

            // بازگشت به مرحله قبل
            backBtn.addEventListener('click', function() {
                step2.classList.remove('active');
                step1.classList.add('active');
                clearInterval(countdown);
                resendBtn.disabled = true;
                mobileInput.focus();
            });

            // تایمر برای ارسال مجدد
            function startTimer() {
                let timeLeft = 120; // 2 دقیقه
                resendBtn.disabled = true;

                countdown = setInterval(() => {
                    timeLeft--;

                    const minutes = Math.floor(timeLeft / 60);
                    const seconds = timeLeft % 60;
                    timer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                    if (timeLeft <= 0) {
                        clearInterval(countdown);
                        resendBtn.disabled = false;
                        timer.textContent = 'کد منقضی شده است';
                    }
                }, 1000);
            }

            function setLoading(type, isLoading) {
                const btn = type === 'send' ? sendCodeBtn : verifyBtn;
                const text = type === 'send' ? document.getElementById('send-btn-text') : document.getElementById('verify-btn-text');
                const loading = type === 'send' ? document.getElementById('send-loading') : document.getElementById('verify-loading');

                if (isLoading) {
                    btn.disabled = true;
                    text.textContent = type === 'send' ? 'در حال ارسال' : 'در حال تأیید';
                    loading.style.display = 'inline-block';
                } else {
                    btn.disabled = false;
                    text.textContent = type === 'send' ? 'ارسال کد تأیید' : 'تأیید و ورود';
                    loading.style.display = 'none';
                }
            }

            function showMessage(text, type, messageElement) {
                messageElement.textContent = text;
                messageElement.className = 'message ' + type;
                messageElement.style.display = 'block';

                if (type === 'error') {
                    setTimeout(() => {
                        messageElement.style.display = 'none';
                    }, 5000);
                }
            }

            // اجازه ارسال با Enter
            mobileInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendCodeBtn.click();
                }
            });

            codeInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    verifyBtn.click();
                }
            });
        });
    </script>
</body>

</html>