const urlBase64ToUint8Array = base64String => {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    const rawData = atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }

    return outputArray;
}

const saveSubscription = async (subscription) => {
    const response = await fetch('http://localhost:3000/save-subscription', {
        method: 'post',
        headers: { 'Content-type': "application/json" },
        body: JSON.stringify(subscription)
    })

    return response.json()
}

self.addEventListener('activate', async (e)=>{
    const subscription = await self.registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array("BJ6kaUF6KBhRSnxHej9fEwuAFtpwLMYxdUYidRb4-VCthYwVqoCD72NrDYrDkKi8RGUKyPTr48rKw2nUEQcSZaY")
    });

    const response = await saveSubscription(subscription)
    // console.log(subscription)
    console.log(response)
})

self.addEventListener("push", e => {
    // console.log(e.data.json());
    // console.log('Received a push message', e.data.json());
    // return 
    const data = e.data.json();
    self.registration.showNotification(data.title, 
        { 
            body : data.body,
            icon: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRDX9uxxgkkLYtb5B_Jdaq5ZGqk3rU6j1srNA&s',
            vibrate: [125,75,125,275,200,275,125,75,125,275,200,600,200,600]
        }
    );
    // const data = e.data.json();
    // self.registration.showNotification(data.title, {
    //     body : data.body,
    //     icon: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRDX9uxxgkkLYtb5B_Jdaq5ZGqk3rU6j1srNA&s',
    //     vibrate: [125,75,125,275,200,275,125,75,125,275,200,600,200,600]
    // });



    // self.registration.showNotification("Wohoo!!", { 
    //     body: e.data.text(),
    //     icon: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRDX9uxxgkkLYtb5B_Jdaq5ZGqk3rU6j1srNA&s',
    //     // badge:'',
    //     image:'https://wallpaperbat.com/img/5820966-fc-barcelona-season-23-24-by-z-a-y-n-o-s.jpg',
    //     vibrate: [125,75,125,275,200,275,125,75,125,275,200,600,200,600]
    // })
})