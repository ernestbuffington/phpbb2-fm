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
import irc.security.*;
import java.lang.reflect.*;

/**
 * Basic interpretor.
 */
public class DescBotInterpretor extends RootInterpretor implements Interpretor
{
	private DescBotConfiguration _DescBotConfiguration;
	private IRCConfiguration _ircConfiguration;

   /*
	* Create a new DescBotInterpretor without default interpretor.
	* @param config the configuration.
	*/
	public DescBotInterpretor(DescBotConfiguration config)
	{
		this(config,null);
	}
	
   /**
	* Create a new DescBotInterpretor.
	* @param config the configuration.
	* @param next next interpretor to be used if the command is unknown. If null,
	* the command will be sent as it to the server.
	*/
	public DescBotInterpretor(DescBotConfiguration config, Interpretor next)
	{
		super(config.getIRCConfiguration(),next);
		_DescBotConfiguration=config;
		_ircConfiguration=config.getIRCConfiguration();
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

   /**
	* Handle the received command.
	* @param source the source that emitted the command.
	* @param cmd the hole command line.
	* @param parts the parsed command line.
	* @param cumul the cumul parsed command line.
	*/
	protected void handleCommand(Source source,String cmd,String[] parts,String[] cumul)
	{
		try
		{
			Server server=source.getServer();
			if(cmd.equals("desc"))
			{
				test(cmd,parts,1);
				_DescBotConfiguration.set("nickresponse",cumul[1]);
			}
			else
			if(cmd.equals("delkey"))
			{
				test(cmd,parts,1);
				_DescBotConfiguration.delResponse(parts[1]);
			}
			else
			if(cmd.equals("addkey"))
			{
				test(cmd,parts,2);
				_DescBotConfiguration.addResponse(parts[1],cumul[2]);
			}
			else
			{
				super.handleCommand(source,cmd,parts,cumul);
			}
		}
		catch(NotEnoughParametersException ex)
		{
			source.report(getText(IRCTextProvider.INTERPRETOR_INSUFFICIENT_PARAMETERS,ex.getMessage()));
		}
	}
	
	public void sendString(Source source,String str)
	{
		if (str.indexOf("$")!= -1 && _DescBotConfiguration.ScriptVariables())
		{
			String myNick=source.getServer().getNick();
			String chan=source.getName();
			str=replace(str,"$me",myNick);
			str=replace(str,"$schan",chan.substring(1,chan.length()));
			str=replace(str,"$chan",chan);
			super.sendString(source,str);
		}
		else super.sendString(source,str);
	}
}