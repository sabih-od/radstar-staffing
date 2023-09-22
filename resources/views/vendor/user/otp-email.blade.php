<style>
    /* Reset some default styles to ensure consistency */
    body, p {
        margin: 0;
        padding: 0;
    }

    /* Center the container */
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background: #dddddd;
    }

    /* Style the box */
    .box {
        width: 60%;
        background-color: #f4f4f4;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    }

    /* Style the text inside the box */
    .box-text {
        font-family: Arial, sans-serif;
        font-size: 16px;
        line-height: 1.5;
    }

    /* Style the button */
    .reset-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #133d6a;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 10px;
    }
</style>
<body>
<div class="container">
    <div class="box">
        <p class="box-text">
            Hello!<br><br>
            You are receiving this email because we received a password reset request for your account.<br><br>
            This is your otp <strong>{{$otp ?? ''}}</strong> your otp is expire in 2 minutes<br>
            @if($type == 'web')
                <a href="{{ route('user.enter.otp.form' , ['id' => $encryptedId]) }}" class="reset-button">Reset
                    Password</a>
            @endif
            <br><br>
            If you did not request a password reset, no further action is required.<br><br>
            Regards,<br>
            Radstar Staffing
        </p>
    </div>
</div>
</body>