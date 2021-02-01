<center>
            <div style="width: 600px; margin: 0 auto; display:block; background-color: #f2f2f2;">
                <center>
                    <table width="600" style="font-family: Arial !important; background-color: #f2f2f2;" cellspacing="0" cellpadding="0" border="0" align="center">
                        <tr>
                            <td>
                                <center><img src="{{URL::asset('images/logo.jpg')}}
                                    " alt="img" style="display:block;" /></center>
                            </td>
                        </tr>
                        <tr>
                            <td style="background-color: #0057d9;">
                                <table width="100%">
                                    <tr>
                                        <td>
                                            <center><img src="{{URL::asset('images/verticalspacer.png')}}" style="display:block;" /></center>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                                <tr>
                                                    <td><img src="{{URL::asset('images/spacer.png')}}" style="display:block;" /></td>
                                                    <td style="font-size: 13px; font-weight: bold; line-height: 23px;">
                                                        <table width="100%">
                                                            <tr>
                                                                <td><div style="font-size: 20px; color: #fff; text-align:center;"><b>Dear User</b></div></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td><img src="{{URL::asset('images/spacer.png')}}" style="display:block;" /></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <center><img src="{{URL::asset('images/verticalspacer.png')}}" style="display:block;" /></center>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <center><img src="{{URL::asset('images/verticalspacer.png')}}" style="display:block;" /></center>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <center><img src="{{URL::asset('images/verticalspacer.png')}}" style="display:block;"></center>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <center><img src="{{URL::asset('images/verticalspacer.png')}}" style="display:block;"></center>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td><img src="{{URL::asset('images/spacer.png')}}" style="display:block;" /></td>
                                        <td style="font-size: 13px; font-weight: bold; line-height: 23px;">
                                            <table width="100%">
                                                <tr>
                                                    <td><div style="font-size: 20px; text-align: center;"><b>We are delighted to inform you that you have been invited by <span style="color:#0057d9;">{{$user->name}}</span> to present the upcoming Conference: </b></div></td>
                                                </tr>
												<tr>
												
                                                <tr>
                                                    <td>
                                                        <center><img src="{{URL::asset('images/verticalspacer.png')}}" style="display:block;"></center>
                                                    </td>
                                                </tr>
                                                <tr>
                                                <td>
                                                    <table cellpadding="0" width="400" align="center" cellspacing="0" style="border: 1px solid #ccc">
                                                        <tbody><tr height="40" style="background-color: #0057d9;">
                                                            <td style="text-align: left; font-family: Arial;font-weight: bold; color: #fff;">&nbsp;&nbsp;&nbsp;&nbsp;Title</td>
                                                            <td style="font-family: Arial;color: #fff; ">&nbsp;&nbsp;&nbsp;&nbsp;{{$detail->name}}</td>
                                                        </tr>
                                                        
														<tr height="40" style="background-color: #0057d9;">
                                                            <td style="text-align: left; border-top: 1px solid #ccc; font-family: Arial; font-weight: bold; color: #fff;">&nbsp;&nbsp;&nbsp;&nbsp;Email</td>
                                                            <td style="font-family: Arial; border-top: 1px solid #ccc; color: #fff;">&nbsp;&nbsp;&nbsp;&nbsp;<span>{{$user->email}}</span></td>
                                                        </tr>

                                                        <tr height="40" style="background-color: #0057d9;">
                                                            <td style="text-align: left; border-top: 1px solid #ccc; font-family: Arial; font-weight: bold; color: #fff;">&nbsp;&nbsp;&nbsp;&nbsp;Organizer/Speaker</td>
                                                            <td style="font-family: Arial; border-top: 1px solid #ccc; color: #fff;">&nbsp;&nbsp;&nbsp;&nbsp;{{$user->first_name}}</td>
                                                        </tr>
														
                                                        <tr height="40" style="background-color: #0057d9;">
                                                            <td style="text-align: left; border-top: 1px solid #ccc; font-family: Arial; font-weight: bold; color: #fff;">&nbsp;&nbsp;&nbsp;&nbsp;Date (MM/DD/YYYY)</td>
                                                            <td style="font-family: Arial; border-top: 1px solid #ccc; color: #fff;">&nbsp;&nbsp;&nbsp;&nbsp;{{date('m-d-Y',strtotime($detail->date))}}</td>
                                                        </tr>

														<tr height="40" style="background-color: #0057d9;">
                                                            <td style="text-align: left; border-top: 1px solid #ccc; font-family: Arial; font-weight: bold; color: #fff;">&nbsp;&nbsp;&nbsp;&nbsp;Time (UTC)</td>
                                                            <td style="font-family: Arial; border-top: 1px solid #ccc; color: #fff;">&nbsp;&nbsp;&nbsp;&nbsp;{{$detail->start_time}}</td>
                                                        </tr>

                                                        <tr height="40" style="background-color: #0057d9;">
                                                            <td style="text-align: left; border-top: 1px solid #ccc; font-family: Arial; font-weight: bold; color: #fff;">&nbsp;&nbsp;&nbsp;&nbsp;End Time (UTC)</td>
                                                            <td style="font-family: Arial; border-top: 1px solid #ccc; color: #fff;">&nbsp;&nbsp;&nbsp;&nbsp;{{$detail->end_time}}</td>
                                                        </tr>
														
                                                        <tr height="40" style="background-color: #0057d9;">
                                                            <td style="text-align: left; border-top: 1px solid #ccc; font-family: Arial; font-weight: bold; color: #fff;">&nbsp;&nbsp;&nbsp;&nbsp;Description</td>
                                                            <td style="font-family: Arial; border-top: 1px solid #ccc; color: #fff;">&nbsp;&nbsp;&nbsp;&nbsp;{{strip_tags($detail->description) }}</td>
                                                        </tr>

                                                        <tr height="40" style="background-color: #0057d9;">
                                                            <td style="text-align: left; border-top: 1px solid #ccc; font-family: Arial; font-weight: bold; color: #fff;">&nbsp;&nbsp;&nbsp;&nbsp;Session Link </td>
                                                            <td style="font-family: Arial; border-top: 1px solid #ccc; color: #fff;">&nbsp;&nbsp;&nbsp;&nbsp;{{$url }}</td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                                </td>
                                            </tr>
                                            </table>
                                        </td>
                                        <td><img src="{{URL::asset('images/spacer.png')}}" style="display:block;" /></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <center><img src="{{URL::asset('images/verticalspacer.png')}}" style="display:block;" /></center>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <center><a href="{{url('conference')}}/{{$detail->u_id}}" style="background-color: #444; width: 200px; color: #fff; padding: 10px; border-radius: 30px; display: block; line-height: 45px; font-weight: 600; font-size: 19px;">Join</a></center>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <center><img src="{{URL::asset('images/verticalspacer.png')}}" style="display:block;" /></center>
                            </td>
                        </tr>
						<tr>
                            <td>
                                <center><img src="{{URL::asset('images/verticalspacer.png')}}" style="display:block;"></center>
                            </td>
                        </tr>
						<tr>
                            <td>
                               <div style="font-size: 20px; text-align: center;">To access the conference with the best experience, please authorize AmbiPlatforms to use your Mic and Camera.</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <center><img src="{{URL::asset('images/verticalspacer.png')}}" style="display:block;" /></center>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <center><img src="{{URL::asset('images/verticalspacer.png')}}" style="display:block;" /></center>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center; font-size: 16px; font-weight: bold;">Best</td>
                        </tr>
                        <tr>
                            <td style="text-align: center; font-size: 16px; font-weight: bold;">The AmbiPlatform Team </td>
                        </tr>

                        <tr>
                            <td>
                                <center><img src="{{URL::asset('images/verticalspacer.png')}}" style="display:block;"></center>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <center><img src="{{URL::asset('images/verticalspacer.png')}}" style="display:block;"></center>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <center><img src="{{URL::asset('images/verticalspacer.png')}}" style="display:block;"></center>
                            </td>
                        </tr>
                        <tr>
                            <td style="background-color: #0057d9;">
                                <table width="100%">
                                    <tr>
                                        <td>
                                            <center><img src="{{URL::asset('images/verticalspacer.png')}}" style="display:block;" /></center>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                                <tr>
                                                    <td><img src="{{URL::asset('images/spacer.png')}}" style="display:block;" /></td>
                                                    <td style="font-size: 13px; font-weight: bold; line-height: 23px;">
                                                        <table width="100%">
                                                            <tr>
                                                                <td><div style=" color: #fff; text-align:center;"><b><a style="color:#fff;" href="#"> 	
                                                                    www.ambiplatforms.com</a></b></div></td>
                                                            </tr>
                                                            <tr>
                                                                <td><div style=" color: #fff; text-align:center;"><b>   
                                                                    Contact us at: <a style="color:#fff;" href="#"> support@AmbiPlatforms.com</a></b></div></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td><img src="{{URL::asset('images/spacer.png')}}" style="display:block;" /></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <center><img src="{{URL::asset('images/verticalspacer.png')}}" style="display:block;" /></center>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <center><img src="{{URL::asset('images/footerborder.jpg')}}" style="display:block;" /></center>
                            </td>
                        </tr>
                    </table>
                </center>
            </div>
        </center> 
