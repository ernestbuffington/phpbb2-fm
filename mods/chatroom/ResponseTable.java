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

class ResponseItem			   
{
	public String textmatch;
	public String response;								 
	
	public ResponseItem(String textmatch,String response)
	{
		this.textmatch=textmatch;
		this.response=response;
	}
}

/*
 *
 * Response table.
 */
public class ResponseTable
{
	private Vector _responseTable;

   /*
    *
	* Create a new, empty, Response table.
	*/
	public ResponseTable()
	{
		_responseTable=new Vector();
	}

   /*
    *
	* Add a Response in the table.
	* @param textmatch the matching text.
	* @param response String, the Response.
	*/
	public void addResponse(String textmatch,String response)
	{
		if(response!=null) _responseTable.insertElementAt(new ResponseItem(textmatch,response),_responseTable.size());
	}

   /*
    *
	* Remove a Response from the table.
	* @param textmatch the matching text.
	*/
	public void delResponse(String textmatch)
	{
		for(int i=0;i<_responseTable.size();i++)
		{
			String key=getTextMatch(i).toLowerCase();
			if(key.equals(textmatch.toLowerCase()))
			{
				System.out.println("Removing Key No:"+i);
				_responseTable.removeElementAt(i);
			}
		}
	}

   /*
	*
	* Get the Responses count.
	* @return the amount of Responses in the table.
	*/
	public int getSize()
	{
		return _responseTable.size();
	}

	/*
	*
	* Get the i'th textmatch in the Response table.
	* @param index table index.
	* @return i'th Response textmatch.
	*/
	public String getTextMatch(int index)
	{
		if(index<0) return null;
		if(index>=getSize()) return null;
		ResponseItem item=(ResponseItem)_responseTable.elementAt(index);
		return item.textmatch;
	}
	
	/*
	* Put together a list of TextResponder keys.
	* @param msg line to test.
	* @return null if there are none.
	*/
	public Enumeration getResponseKeys()
	{
		int s=getSize();
		Vector Keys=new Vector();
		for(int i=0;i<s;i++)
		{ 
			Keys.addElement(getTextMatch(i));
		}
		Enumeration responseKeys=Keys.elements();
		return responseKeys;
	}

	/*
	*
	* Get the i'th String in the Response table.
	* @param index table index.
	* @return i'th String.
	*/
	public String getResponse(int index)
	{
		if(index<0) return null;
		if(index>=getSize()) return null;
		ResponseItem item=(ResponseItem)_responseTable.elementAt(index);
		return item.response;
	}
}
