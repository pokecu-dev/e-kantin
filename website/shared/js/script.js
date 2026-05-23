
document.addEventListener('submit', async (event)=> {

    const form = event.target;
    if (form.getAttribute('data-ajax') !== 'true') return;

    event.preventDefault();

    const url = form.getAttribute('data-action');
    const notifID = form.getAttribute('data-notif');
    const notifElement = document.getElementById(notifID);

    if (!notifElement) return;

    const form_data = new FormData(form);

    try{

        const response = await fetch(url,{
            method: 'POST',
            body: form_data
        });

        const dataR = await response.json();

        if (dataR) {
            notifElement.innerText = dataR.message;
        }

    }
    catch(error){
        console.log("error",error);
    }

});