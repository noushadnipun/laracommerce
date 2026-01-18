window.ElegantNotification=(function(){
  const icons={success:'✓',error:'✕',warning:'⚠',info:'ℹ'};
  function title(type){return({success:'Success!',error:'Error!',warning:'Warning!',info:'Info'})[type]||'Info'}
  function container(){let c=document.getElementById('elegant-notification-container');if(!c){c=document.createElement('div');c.id='elegant-notification-container';document.body.appendChild(c);}return c}
  function hide(id){const n=document.getElementById(id);if(n){n.classList.remove('show');setTimeout(()=>{n.parentNode&&n.parentNode.removeChild(n)},400)}}
  function show(message,type='info',duration=4000){const c=container();const n=document.createElement('div');const id='notification-'+Date.now();n.className='elegant-notification '+type;n.id=id;n.innerHTML=`
    <button class="notification-close" onclick="ElegantNotification.hide('${id}')">×</button>
    <div class="notification-header">
      <div class="notification-icon ${type}">${icons[type]||icons.info}</div>
      <div class="notification-content">
        <div class="notification-title">${title(type)}</div>
        <div class="notification-message">${message}</div>
      </div>
    </div>
    <div class="notification-progress ${type}"><div class="notification-progress-bar" id="progress-${id}"></div></div>`;
    c.appendChild(n);setTimeout(()=>n.classList.add('show'),100);
    const bar=document.getElementById('progress-'+id);let p=0;const iv=setInterval(()=>{p+=1;bar.style.transform='translateX(-'+(100-p)+'%)';if(p>=100){clearInterval(iv);hide(id)}},duration/100);
    setTimeout(()=>hide(id),duration);return id}
  const api={show,hide,success:(m,d)=>show(m,'success',d),error:(m,d)=>show(m,'error',d),warning:(m,d)=>show(m,'warning',d),info:(m,d)=>show(m,'info',d)};return api})();
window.alert=function(m){ElegantNotification.info(m,3000)}


