/*****************************************************/
/*          This java file is a part of the          */
/*                                                   */
/*          -  ADnD.com DescBot Plugin for -         */
/*           -  Plouf's Java IRC Client  -           */
/*                                                   */
/*         Copyright (C)  2003 - 2004 Thema          */
/*                                                   */
/*           All contacts : thema@adnd.com           */
/*                                                   */
/*  PJIRC is free software; you can redistribute     */
/*  it and/or modify it under the terms of the GNU   */
/*  General Public License as published by the       */
/*  Free Software Foundation; version 2 or later of  */
/*  the License.                                     */
/*                                                   */
/*  PJIRC is distributed in the hope that it will    */
/*  be useful, but WITHOUT ANY WARRANTY; without     */
/*  even the implied warranty of MERCHANTABILITY or  */
/*  FITNESS FOR A PARTICULAR PURPOSE.  See the GNU   */
/*  General Public License for more details.         */
/*                                                   */
/*  You should have received a copy of the GNU       */
/*  General Public License along with PJIRC; if      */
/*  not, write to the Free Software Foundation,      */
/*  Inc., 59 Temple Place, Suite 330, Boston,        */
/*  MA  02111-1307  USA                              */
/*                                                   */
/*****************************************************/

package irc.plugin.adnd;

import irc.*;
import java.util.*;
import irc.plugin.*;

class NullItem
{
}

public class DescBot extends Plugin implements SourceListener
{
	private DescBotConfiguration _DescBotConfiguration;

	public DescBot(IRCConfiguration config) throws Exception
	{
		super(config);
	}
	
	public void load()
    {
		super.load();
		try
		{
			_DescBotConfiguration=new DescBotConfigurationLoader(_ircConfiguration).loadDescBotConfiguration();
		}
		catch(Exception ex)
		{
			ex.printStackTrace();
			throw new Error(ex.getMessage());
		}
		
		System.out.println("Loaded DescBot:");
    } 
	
	public void sourceCreated(Source source,Boolean bring)
	{
		source.addSourceListener(this);
		source.setInterpretor(new DescBotInterpretor(_DescBotConfiguration,source.getInterpretor())); 
	}
	
	public void sourceRemoved(Source source)
	{
		source.removeSourceListener(this);
	}  
	
	private String replace(String on,String what,String with)
	{
		int pos=on.indexOf(what);
		while(pos>=0)
		{
			String before=on.substring(0,pos);
			String after=on.substring(pos+what.length());
			on=before+with+after;
			pos=on.indexOf(what);
		}
		return on;
	}

   /* Test whether the NickResponder should notice some text to the sender
	* @param msg line to test.
	* @return true if notice should be sent, otherwise false.
	*/
	protected boolean needNickResponse(String key, String match)
	{
		if(_DescBotConfiguration.NickResponder())
		{
			if(key.equals(match)) return true;
		}
		return false;
	}

   /*
	* Test whether the TextResponder should notice some text to the sender
	* @param msg line to test.
	* @return true if notice should be sent, otherwise false.
	*/
	protected String getTextResponse(String key, String cmd, String nick)
	{
		ResponseTable table=_DescBotConfiguration.getResponseTable();
		int s=table.getSize();
		String match="";
		
		for(int i=0;i<s;i++)
		{
			match=cmd+table.getTextMatch(i);
			match=replace(match,"$me",nick).toLowerCase();
			if (key.equals(match))
			{
				return table.getResponse(i);
			}
		}
		return "";
	}

	public void messageReceived(String nick,String msg,Source source)
	{
		String cmd=_DescBotConfiguration.getS("respondercmd");
		String chan=source.getName();
		String response="";
		String key="";
		String myNick=source.getServer().getNick();

		if (msg.startsWith(cmd))
		{
		 	int pos=msg.indexOf(' ');
			if(pos==-1)
			{
				key=msg.toLowerCase();
			}
			else
			{
				key=msg.substring(0,pos).toLowerCase();
			}	  
		
			String match=cmd+myNick.toLowerCase();
			if (key.equals(match+"_help"))
			{
				ResponseTable table=_DescBotConfiguration.getResponseTable();
				int idx=table.getSize();
				String nKey="";
				Enumeration keys=table.getResponseKeys();
				source.getServer().execute("NOTICE "+nick+" :\1BOT: Typing these keys will elicit more information.\1");
				while (keys.hasMoreElements())
				{
					nKey=(String)keys.nextElement();
					nKey=replace(nKey,"$me",myNick); 
					source.getServer().execute("NOTICE "+nick+" :\1KEY: "+cmd+nKey+"\1");
				}
			}
			else if(needNickResponse(key, match))
			{
				response=_DescBotConfiguration.getS("nickresponse");
				if (response!="")
				{ 										   
					if (response.indexOf("$me")!=-1)
					{
						response=replace(response,"$me",myNick);
					}	
					if (response.indexOf("$schan")!=-1)
					{
						if (chan.toLowerCase().trim().equals(nick.toLowerCase().trim()))
						{
							response=replace(response,"$schan","privmsg");
						}
						else response=replace(response,"$schan",chan.substring(1,chan.length()));
					}	
					if (response.indexOf("$chan")!=-1)
					{
						if (chan.toLowerCase().trim().equals(nick.toLowerCase().trim()))
						{
							response=replace(response,"$chan","privmsg");
						}
						else response=replace(response,"$chan",chan);
					}
					if (response.indexOf("$nick")!=-1)
					{		
						response=replace(response,"$nick",nick);
					}
					source.getServer().execute("NOTICE "+nick+" :\1DESC "+response+"\1");
				}
			}
			else if(_DescBotConfiguration.TextResponders())
			{
				key=replace(key,"$me",myNick);
				response=getTextResponse(key, cmd, myNick);
				if (response!="")
				{ 
					if (response.indexOf("$me")!=-1)
					{
						response=replace(response,"$me",myNick);
					}
					if (response.indexOf("$schan")!=-1)
					{
						if (chan.toLowerCase().trim().equals(nick.toLowerCase().trim()))
						{
							response=replace(response,"$schan","privmsg");
						}
						else response=replace(response,"$schan",chan.substring(1,chan.length()));
					}	
					if (response.indexOf("$chan")!=-1)
					{
						if (chan.toLowerCase().trim().equals(nick.toLowerCase().trim()))
						{
							response=replace(response,"$chan","privmsg");
						}
						else response=replace(response,"$chan",chan);
					}
					if (response.indexOf("$nick")!=-1)
					{					
						response=replace(response,"$nick",nick);
					}
					source.getServer().execute("NOTICE "+nick+" :\1BOT "+response+"\1");
				}
			}
		}
	}
	
	public void reportReceived(String message,Source source)
	{
	}
	
	public void noticeReceived(String nick,String message,Source source)
	{
	}
	
	public void action(String nick,String msg,Source source)
	{
	}
	
	public void clear(Source source)
	{
	}
	
	public void unload()
	{
		_DescBotConfiguration=null;
		super.unload();
	}
}
