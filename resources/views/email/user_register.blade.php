<table cellspacing="0" cellpadding="0" border="0" height="100%" width="100%">
   <tbody>
      <tr>
         <td align="center" valign="top" style="padding:20px 0 20px 0">
            <table bgcolor="FFFFFF" cellspacing="0" cellpadding="10" border="0" width="650" style="border:1px solid #e0e0e0">
               <tbody>
                  <tr>
                     <td valign="top">
                        <a href="{{$url_web}}" target="_blank" style="font-size: 30px; font-weight: bold; color: #F04F32; padding: 15px; text-align: center; display: block;"><img src="{{asset('img/thank-you-mail.jpg')}}" alt="" style="width: 100%"></a>
                     </td>
                  </tr>
                  <tr>
                     <td valign="top">
                        <h1 style="font-size:22px;font-weight:normal;line-height:22px;margin:0 0 11px 0">Thân gửi, <span style="color: #F04F32">{{$your_name}}</span></h1>
                        <p style="font-size:12px;line-height:16px;margin:0 0 8px 0">Cảm ơn bạn đã đăng ký thành viên tại website {{$url_web}}.</p>
                        <p style="font-size:12px;line-height:16px;margin:0 0 8px 0">Thông tin đăng nhập:</p>
                        <ul>
                           <li>Username: <strong>{{$your_email}}</strong></li>
                           <li>Password: {{$your_pass}}</li>
                        </ul>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>