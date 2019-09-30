using Microsoft.AspNet.Identity;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using wealthTracker.Models;
using System.Threading.Tasks;
using System.Data.Entity;

namespace wealthTracker.Providers
{
    using Microsoft.AspNet.Identity;
    using System.Security.Cryptography;
    using System.Text;

    public class MyPasswordHasher : IPasswordHasher
    {
        private static string hashKey = "aBCd#er4590ef#$5sha1234WT";
        public string HashPassword(string password)
        {
            return MyPasswordHasher.Encrypt (password,true);
        }
        
        public PasswordVerificationResult VerifyHashedPassword(string hashedPassword, string providedPassword)
        {
            if (hashedPassword == HashPassword(providedPassword))
                return PasswordVerificationResult.Success;
            else
                return PasswordVerificationResult.Failed;
        }

        public static string Encrypt(string toEncrypt, bool useHashing)
        {
            byte[] keyArray;
            byte[] toEncryptArray = UTF8Encoding.UTF8.GetBytes(toEncrypt);

            //System.Configuration.AppSettingsReader settingsReader = new AppSettingsReader();
            // Get the key from config file

            string key = hashKey;
            //System.Windows.Forms.MessageBox.Show(key);
            //If hashing use get hashcode regards to your key
            if (useHashing)
            {
                MD5CryptoServiceProvider hashmd5 = new MD5CryptoServiceProvider();
                keyArray = hashmd5.ComputeHash(UTF8Encoding.UTF8.GetBytes(key));
                //Always release the resources and flush data
                // of the Cryptographic service provide. Best Practice

                hashmd5.Clear();
            }
            else
                keyArray = UTF8Encoding.UTF8.GetBytes(key);

            TripleDESCryptoServiceProvider tdes = new TripleDESCryptoServiceProvider();
            //set the secret key for the tripleDES algorithm
            tdes.Key = keyArray;
            //mode of operation. there are other 4 modes.
            //We choose ECB(Electronic code Book)
            tdes.Mode = CipherMode.ECB;
            //padding mode(if any extra byte added)

            tdes.Padding = PaddingMode.PKCS7;

            ICryptoTransform cTransform = tdes.CreateEncryptor();
            //transform the specified region of bytes array to resultArray
            byte[] resultArray = cTransform.TransformFinalBlock(toEncryptArray, 0, toEncryptArray.Length);
            //Release resources held by TripleDes Encryptor
            tdes.Clear();
            //Return the encrypted data into unreadable string format
            return Convert.ToBase64String(resultArray, 0, resultArray.Length);
        }

        public static string Decrypt(string cipherString, bool useHashing)
        {
            byte[] keyArray;
            //get the byte code of the string

            byte[] toEncryptArray = Convert.FromBase64String(cipherString);

            //Get your key from config file to open the lock!
            string key = hashKey;

            if (useHashing)
            {
                //if hashing was used get the hash code with regards to your key
                MD5CryptoServiceProvider hashmd5 = new MD5CryptoServiceProvider();
                keyArray = hashmd5.ComputeHash(UTF8Encoding.UTF8.GetBytes(key));
                //release any resource held by the MD5CryptoServiceProvider

                hashmd5.Clear();
            }
            else
            {
                //if hashing was not implemented get the byte code of the key
                keyArray = UTF8Encoding.UTF8.GetBytes(key);
            }

            TripleDESCryptoServiceProvider tdes = new TripleDESCryptoServiceProvider();
            //set the secret key for the tripleDES algorithm
            tdes.Key = keyArray;
            //mode of operation. there are other 4 modes. 
            //We choose ECB(Electronic code Book)

            tdes.Mode = CipherMode.ECB;
            //padding mode(if any extra byte added)
            tdes.Padding = PaddingMode.PKCS7;

            ICryptoTransform cTransform = tdes.CreateDecryptor();
            byte[] resultArray = cTransform.TransformFinalBlock(toEncryptArray, 0, toEncryptArray.Length);
            //Release resources held by TripleDes Encryptor                
            tdes.Clear();
            //return the Clear decrypted TEXT
            return UTF8Encoding.UTF8.GetString(resultArray);
        }
    }

    public class UserStoreService : IUserStore<AppUser>, IUserPasswordStore<AppUser>
    {
        DAL.WealthTrackerIdentityDB context = new DAL.WealthTrackerIdentityDB();

        public Task CreateAsync(AppUser user)
        {
            throw new NotImplementedException();
        }

        public Task DeleteAsync(AppUser user)
        {
            throw new NotImplementedException();
        }

        public Task<AppUser> FindByIdAsync(string userId)
        {
            throw new NotImplementedException();
        }

        public Task<AppUser> FindByNameAsync(string userName)
        {
            Task<AppUser> task = context.AppUsers.Where(
                                  apu => apu.UserEmail == userName)
                                  .FirstOrDefaultAsync();

            return task;
        }

        public Task UpdateAsync(AppUser user)
        {
            throw new NotImplementedException();
        }

        public void Dispose()
        {
            context.Dispose();
        }

        public Task<string> GetPasswordHashAsync(AppUser user)
        {
            if (user == null)
            {
                throw new ArgumentNullException("user");
            }

            return Task.FromResult(user.UserPassword);
        }

        public Task<bool> HasPasswordAsync(AppUser user)
        {
            return Task.FromResult(user.UserPassword != null);
        }

        public Task SetPasswordHashAsync(AppUser user, string passwordHash)
        {
            MyPasswordHasher hashedpassword = new MyPasswordHasher();
            user.UserPassword = hashedpassword.HashPassword(user.UserPassword);
            return Task.FromResult(user.UserPassword);
        }

        Task IUserPasswordStore<AppUser, string>.SetPasswordHashAsync(AppUser user, string passwordHash)
        {
            MyPasswordHasher hashedpassword = new MyPasswordHasher();
            user.UserPassword = hashedpassword.HashPassword(user.UserPassword);
            return Task.FromResult(user.UserPassword);
        }

        Task<string> IUserPasswordStore<AppUser, string>.GetPasswordHashAsync(AppUser user)
        {
            if (user == null)
            {
                throw new ArgumentNullException("user");
            }

            return Task.FromResult(user.UserPassword);
        }

        Task<bool> IUserPasswordStore<AppUser, string>.HasPasswordAsync(AppUser user)
        {
            return Task.FromResult(user.UserPassword != null);
        }

        Task IUserStore<AppUser, string>.CreateAsync(AppUser user)
        {
            // Saves the user in your storage.
            context.AppUsers.Add(user);
            context.SaveChanges();
            return Task.FromResult(user);
        }

        Task IUserStore<AppUser, string>.UpdateAsync(AppUser user)
        {
            throw new NotImplementedException();
        }

        Task IUserStore<AppUser, string>.DeleteAsync(AppUser user)
        {
            throw new NotImplementedException();
        }

        Task<AppUser> IUserStore<AppUser, string>.FindByIdAsync(string userId)
        {
            Task<AppUser> task = context.AppUsers.Where(
                                  apu => apu.Id == userId)
                                  .FirstOrDefaultAsync();

            return task;
        }

        Task<AppUser> IUserStore<AppUser, string>.FindByNameAsync(string userName)
        {
            Task<AppUser> task = context.AppUsers.Where(
                                  apu => apu.UserEmail == userName)
                                  .FirstOrDefaultAsync();

            return task;
        }
    }
}