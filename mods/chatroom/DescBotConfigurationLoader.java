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
import irc.plugin.*;

import java.util.*;
import java.awt.*;

/**
 * Toolkit for Configuration creation.
 */
public class DescBotConfigurationLoader
{
	private IRCConfiguration _config;
	private ParameterProvider _provider;
	/**
	* Create a new DescBotConfigurationLoader.
	* @param config the irc configuration.
	*/
	public DescBotConfigurationLoader(IRCConfiguration config)
	{
		_config=config;
		_provider=new PrefixedParameterProvider(_config.getParameterProvider(),"descbot:");
	} 
	/**
	* Create a new DescBotConfiguration object, using the given IRCConfiguration.
	* @return a new DescBotConfiguration instance.
	* @throws Exception if an error occurs.
	*/
	public DescBotConfiguration loadDescBotConfiguration() throws Exception
	{
		return getDescBotConfiguration();
	}
	private String getParameter(String key)
	{
		return _provider.getParameter(key);
	}
	private boolean getBoolean(String key,boolean def)
	{
		String v=getParameter(key);
		if(v==null) return def;
		v=v.toLowerCase().trim();
		if(v.equals("true") || v.equals("on") || v.equals("1")) return true;
		return false;
	}
	private String getString(String key,String def)
	{
		String v=getParameter(key);
		if(v==null) return def;
		return v;
	}
	private int getInt(String key,int def)
	{
		String v=getParameter(key);
		if(v==null) return def;
		try
		{
			return Integer.parseInt(v);
		}
		catch(Exception e)
		{
			return def;
		}
	}
	private String[] getArray(String name)
	{
		Vector v=new Vector();
		String cmd;
		int i=1;
		do
		{
			cmd=getParameter(name+i);
			if(cmd!=null) v.insertElementAt(cmd,v.size());
			i++;
		} while(cmd!=null);
		String[] ans=new String[v.size()];
		for(i=0;i<v.size();i++) ans[i]=(String)v.elementAt(i);
		return ans;
	}
	private void add(Vector v,Object o)
	{
		v.insertElementAt(o,v.size());
	}
	private void str2(Vector v,DescBotConfiguration c,int id,String str)
	{
		add(v,new String[] {c.getText(id),str});
	}
	private void readTextResponders(DescBotConfiguration config)
	{
		String[] arr=getArray("textresponder");
		for(int i=0;i<arr.length;i++)
		{
			String cmd=arr[i];
			int pos=cmd.indexOf(" ");
			if(pos!=-1)
			{
				String textmatch=cmd.substring(0,pos).trim();
				String response=cmd.substring(pos+1).trim();
				config.addResponse(textmatch,response);
			}
		}
	}
	private DescBotConfiguration getDescBotConfiguration() throws Exception
	{
		DescBotConfiguration config=new DescBotConfiguration(_config);
		config.set("nickresponder",getBoolean("nickresponder",false));
		config.set("textresponders",getBoolean("textresponders",false));
		config.set("nickresponse",getString("nickresponse",""));
		config.set("respondercmd",getString("respondercmd","!"));
		config.set("scriptvariables",getBoolean("scriptvariables",false));
		readTextResponders(config);
		return config;
	}
}
