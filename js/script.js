"user strict"

let alert = document.querySelectorAll('.alert');
alert.forEach((e)=>{
    
    setTimeout(()=>{
        e.style.display = 'none';
    }, 4000);
});



