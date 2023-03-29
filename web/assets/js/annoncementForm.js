const input = document.querySelector('#createAnnouncement input[name="createCategory"]');
const select = document.querySelector('#createAnnouncement select');

if(input && select){
   select.addEventListener("change", (e) => {
      console.log(select.value)
   })
}

console.log("oui");