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

public class DescBotConfiguration
{
  private IRCConfiguration _config;
  private ResponseTable _responseTable;
  private NullItem NULL_ITEM=new NullItem();
  private Hashtable _htable;
  private TextProvider _textProvider;

  public DescBotConfiguration(IRCConfiguration config)
  {
    _config=config;
	_responseTable=new ResponseTable();
    _htable=new Hashtable();
  }
  
  public void setTextProvider(TextProvider txt)
  {
    _textProvider=txt;
  }

  /**
  * Get the high version number.
  * @return the high version number.
  */
  public int getVersionHigh()
  {
    return 2;
  }

  /**
  * Get the middle version number.
  * @return the middle version number.
  */
  public int getVersionMed()
  {
    return 2;
  }

  /**
  * Get the low version number.
  * @return the low version number.
  */
  public int getVersionLow()
  {
    return 1;
  }

  /**
  * Get the version modifiers.
  * @return version modifiers.
  */
  public String getVersionModifiers()
  {
    return "DescBot(Alpha)";
  }

  /**
  * Get version number as a string.
  * @return high.med.lowmod version number.
  */
  public String getVersion()
  {
    return getVersionHigh()+"."+getVersionMed()+"."+getVersionLow()+getVersionModifiers();
  }

  /**
  * Set the given property to the given value. This value may be null.
  * @param key property name.
  * @param obj property value.
  */
  public synchronized void set(String key,Object obj)
  {
    if(obj==null) obj=NULL_ITEM;
    _htable.put(key.toLowerCase(),obj);
  }

  /**
  * Set the given property to the given int value.
  * @param key property name.
  * @param val property value.
  */
  public synchronized void set(String key,int val)
  {
    set(key,new Integer(val));
  }

  /**
  * Set the given property to the given boolean value.
  * @param key property name.
  * @param val property value.
  */
  public synchronized void set(String key,boolean val)
  {
    set(key,new Boolean(val));
  }

  /**
  * Get the given property value.
  * @param key property name.
  * @return the property value.
  * @throws RuntimeException if the property is unknown.
  */
  public synchronized Object get(String key)
  {
    Object v=_htable.get(key.toLowerCase());
    if(v==null) throw new RuntimeException("Unknown configuration property "+key);
    if(v==NULL_ITEM) v=null;
    return v;
  }

  /**
  * Get the given property value as an int value.
  * @param key property name.
  * @return the property value.
  * @throws RuntimeException if the property is unknown.
  */
  public synchronized int getI(String key)
  {
    Integer i=(Integer)get(key);
    return i.intValue();
  }

  /**
  * Get the given property value as a boolean value.
  * @param key property name.
  * @return the property value.
  * @throws RuntimeException if the property is unknown.
  */
  public synchronized boolean getB(String key)
  {
    Boolean b=(Boolean)get(key);
    return b.booleanValue();
  }

  /**
  * Get the given property value as String value.
  * @param key property name.
  * @return the property value.
  * @throws RuntimeException if the property is unknown.
  */
  public synchronized String getS(String key)
  {
    return (String)get(key);
  }

  /**
  * Get the response table.
  * @return the response table.
  */
  public ResponseTable getResponseTable()
  {
	  return _responseTable;
  }

  /**
  * Add a response in the response table.
  * @param match the matching text to respond to.
  * @param response the text to respond with.
  */
  public synchronized void addResponse(String textmatch,String response)
  {
	  _responseTable.addResponse(textmatch,response);
  }

  /**
  * Add a response in the response table.
  * @param match the matching text to respond to.
  * @param response the text to respond with.
  */
  public synchronized void delResponse(String textmatch)
  {
	  _responseTable.delResponse(textmatch);
  }

  /**
  * Get nick responder  flag.
  * @return nick responder flag.
  */
  public synchronized boolean NickResponder()
  {
    return getB("nickresponder");
  }

  /**
  * Get text responder  flag.
  * @return text responder flag.
  */
  public synchronized boolean TextResponders()
  {
    return getB("textresponders");
  }
  /**
  * Get text responder  flag.
  * @return text responder flag.
  */
  public synchronized boolean ScriptVariables()
  {
    return getB("scriptvariables");
  }
  
  public IRCConfiguration getIRCConfiguration()
  {
    return _config;
  }
  /**
   * Get formatted text associated with the given text code, with no parameter.
   * @param code text code.
   * @return formatted text.
   */
  public synchronized String getText(int code)
  {
    if(code<TextProvider.USER_BASE) return _config.getText(code);
    return _textProvider.getString(code);
  }

  /**
   * Get formatted text associated with the given text code, with one parameter.
   * @param code text code.
   * @param p1 first parameter.
   * @return formatted text.
   */
  public synchronized String getText(int code,String p1)
  {
    if(code<TextProvider.USER_BASE) return _config.getText(code,p1);
    return _textProvider.getString(code,p1);
  }

  /**
   * Get formatted text associated with the given text code, with two parameters.
   * @param code text code.
   * @param p1 first parameter.
   * @param p2 second parameter.
   * @return formatted text.
   */
  public synchronized String getText(int code,String p1,String p2)
  {
    if(code<TextProvider.USER_BASE) return _config.getText(code,p1,p2);
    return _textProvider.getString(code,p1,p2);
  }

  /**
   * Get formatted text associated with the given text code, with three parameters.
   * @param code text code.
   * @param p1 first parameter.
   * @param p2 second parameter.
   * @param p3 third parameter.
   * @return formatted text.
   */
  public synchronized String getText(int code,String p1,String p2,String p3)
  {
    if(code<TextProvider.USER_BASE) return _config.getText(code,p1,p2,p3);
    return _textProvider.getString(code,p1,p2,p3);
  }

}
