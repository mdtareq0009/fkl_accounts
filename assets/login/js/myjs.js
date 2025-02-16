$(document).ready(()=>{
    setInterval(function() {
    
        var currentTime = new Date ( );    

        var currentHours = currentTime.getHours ( );   

        var currentMinutes = currentTime.getMinutes ( );   

        var currentSeconds = currentTime.getSeconds ( );

        currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;   

        currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;    

        var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";    

        currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;    

        currentHours = ( currentHours == 0 ) ? 12 : currentHours;    

        var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;

        document.getElementById("timer").innerHTML = currentTimeString;

    }, 1000);

  
})


$(document).on('click', '.branch-access', function(){
    let id = $(this).data('id');
    Swal.fire({
        title: '<strong>Are you sure!</strong>',
        html: 'Want to change branch?',
        showDenyButton: true,
        confirmButtonText: `Ok`,
        denyButtonText: `Cancel`,
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post('/branch_change', {id}).then(res=>{
                let r = res.data;
                Swal.fire({
                    icon: 'success',
                    title: r.message,
                    showConfirmButton: false,
                    timer: 1500
                })
                setTimeout(()=>{
                    window.location = '/';
                }, 1500)
                
            }).catch(error => {
                let e = error.response.data;

                if(e.hasOwnProperty('message')){
                    if(e.hasOwnProperty('errors')){
                        Object.entries(e.errors).forEach(([key, val])=>{
                            this.$toaster.error(val[0]);
                        })
                    }else{
                        this.$toaster.error(e.message);
                    }
                }else{
                    this.$toaster.error(e);
                }
            })
        }
    })
})