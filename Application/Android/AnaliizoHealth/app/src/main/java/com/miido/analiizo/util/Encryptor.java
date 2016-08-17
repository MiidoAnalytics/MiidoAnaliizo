package com.miido.analiizo.util;

import android.util.Log;

import java.io.UnsupportedEncodingException;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

/**
 *
 * @author Alvaro Salagado MIIDO S.A.S 03/03/2015.
 * @version 1.0
 */
public class Encryptor {

    //private String key; //use for an own encrypt algorithm

    /**
     * Contructor
     */
    public Encryptor() {}

    /**
     * Encripta una cadena de texto a MD5 en codificación UTF-8
     * @param s texto a encriptar
     * @return texto encriptado
     */
    public static String md5_UTF(String s) {
        try {
            MessageDigest digest = java.security.MessageDigest.getInstance("MD5");
            digest.update(s.getBytes());
            byte messageDigest[] = digest.digest();
            StringBuilder hexString = new StringBuilder();
            for (byte aMessageDigest : messageDigest)
                hexString.append(Integer.toHexString(0xFF & aMessageDigest));
            return hexString.toString();
        } catch (Exception e) {
            Log.i("MD5", e.getMessage());
        }
        return null;
    }

    /**
     * Encripta una cadena de texto a MD5 en formato exadecimal
     * @param string texto a encriptar
     * @return texto encriptado
     */
    public static String md5_HEX(String string) {
        try {
            byte[] hash;
            hash = MessageDigest.getInstance("MD5").digest(string.getBytes("UTF-8"));
            StringBuilder hex = new StringBuilder(hash.length * 2);
            for (byte b : hash) {
                int i = (b & 0xFF);
                if (i < 0x10) hex.append('0');
                hex.append(Integer.toHexString(i));
            }
            return hex.toString();
        } catch (NoSuchAlgorithmException e) {
            //throw new RuntimeException("Huh, MD5 should be supported?", e);
        } catch (UnsupportedEncodingException e) {
            e.printStackTrace();
        }
        return null;
    }
}