<style>
#global-loader{
    position: fixed;
    inset: 0;
    background: rgba(255,255,255,0.7);
    backdrop-filter: blur(5px);
    z-index: 999999;
    display: none;
    align-items: center;
    justify-content: center;
}

.loader-box{
    background: white;
    padding: 20px 30px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.loader-spinner{
    width: 45px;
    height: 45px;
    border: 4px solid #e5e7eb;
    border-top: 4px solid #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: auto;
}

@keyframes spin{
    0%{ transform: rotate(0deg);}
    100%{ transform: rotate(360deg);}
}
</style>

<div id="global-loader">
    <div class="loader-box">
        <div class="loader-spinner"></div>

        <p style="margin-top:10px;font-size:14px;font-weight:600">
            Please wait...
        </p>

        <p style="font-size:12px;color:#777">
            File is uploading and processing
        </p>
    </div>
</div>

<script>

function showLoader(){
     console.log("Loader function called");
    document.getElementById('global-loader').style.display = 'flex';
}

function hideLoader(){
    document.getElementById('global-loader').style.display = 'none';
}

</script>
