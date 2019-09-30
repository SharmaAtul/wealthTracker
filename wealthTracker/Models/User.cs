using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Linq;
using System.Web;
using Microsoft.AspNet.Identity;
using System.Data.Common;
using System.Data.SqlClient;
using wealthTracker.DAL;
using System.Threading.Tasks;
using System.Data;
using Newtonsoft.Json;
using wealthTracker.Controllers;

namespace wealthTracker.Models
{
    public class UserDetail 
    {
        [Key]
        public string TestKey { get; set; }
        public string TestField { get; set; }

    }
    public class AppUser : IUser
    {
        
        private int userID;
        [DatabaseGeneratedAttribute(DatabaseGeneratedOption.Identity)]
        public int UserID { get; set; }

        private string userEmail;
        [Key]
        public string UserEmail { get; set; }

        private string userPassword;
        public string UserPassword { get; set; }

        private bool isActive;
        public bool IsActive { get; set; }

        private string firstName;
        public string FirstName { get; set; }

        private string lastName;
        public string LastName { get; set; }

        private DateTime? dob;

        [JsonConverter(typeof(CustomDateTimeConverter))]
        public DateTime? DOB { get; set; }

        private string spouseFirstName;
        public string SpouseFirstName { get; set; }

        private string spouseLastName;
        public string SpouseLastName { get; set; }

        private DateTime? spouseDOB;

        [JsonConverter(typeof(CustomDateTimeConverter))]
        public DateTime? SpouseDOB { get; set; }

        private string userType;
        public string UserType { get; set; }

        private int clientId;
        public int ClientId { get; set; }

        public AppUser()
        {
            this.Id = Guid.NewGuid().ToString();
        }

        [NotMapped]
        public string Id
        {
            get; set;
        }
        [NotMapped]
        public string UserName
        {
            get
            {
                return UserEmail;
            }

            set
            {
                UserEmail = value;
            }
        }

        WealthTrackerIdentityDB db = new WealthTrackerIdentityDB();

        public List<AppUser> GetAll(int clientId, int selectedClientId, int currentPage, int pageSize, out int totalCount) {
            db.Database.Connection.Open();

            DbCommand cmd = db.Database.Connection.CreateCommand();
            cmd.CommandText = "proc_Users_getAllPagedN";
            cmd.CommandType = System.Data.CommandType.StoredProcedure;
            cmd.Parameters.Add(new SqlParameter("clientId",  clientId));
            cmd.Parameters.Add(new SqlParameter("selectedClientId", selectedClientId));
            cmd.Parameters.Add(new SqlParameter("currentPage", currentPage));
            cmd.Parameters.Add(new SqlParameter("pageSize", pageSize));
            var totalCountParam = new SqlParameter("totalCount", 0) { Direction = ParameterDirection.Output };
            cmd.Parameters.Add(totalCountParam);
            List<AppUser> users;
            using (var reader = cmd.ExecuteReader())
            {
                users = reader.MapToList<AppUser>();
            }

            //Access output variable after reader is closed
            totalCount = (totalCountParam.Value == null) ? 0 : Convert.ToInt32(totalCountParam.Value);
            
            return users;
        }

        public static AppUser GetById(int id)
        {
            WealthTrackerIdentityDB db = new WealthTrackerIdentityDB();
            db.Database.Connection.Open();

            DbCommand cmd = db.Database.Connection.CreateCommand();
            cmd.CommandText = "proc_Users_getById";
            cmd.Parameters.Add(new SqlParameter("userId", id));
            cmd.CommandType = System.Data.CommandType.StoredProcedure;

            AppUser user;
            using (var reader = cmd.ExecuteReader())
            {
                user = reader.MapToList<AppUser>()[0];
            }

            return user;
        }

        public bool Update()
        {

            db.Database.Connection.Open();

            DbCommand cmd = db.Database.Connection.CreateCommand();
            cmd.CommandText = "proc_Users_Update";

            cmd.CommandType = System.Data.CommandType.StoredProcedure;
            cmd.Parameters.Add(new SqlParameter("userEmail", this.UserEmail));
            cmd.Parameters.Add(new SqlParameter("isActive", this.IsActive));
            cmd.Parameters.Add(new SqlParameter("firstName", this.FirstName));
            cmd.Parameters.Add(new SqlParameter("lastName", this.LastName));
            cmd.Parameters.Add(new SqlParameter("dob", this.DOB));
            cmd.Parameters.Add(new SqlParameter("spouseFirstName", this.SpouseFirstName));
            cmd.Parameters.Add(new SqlParameter("spouseLastName", this.SpouseLastName));
            cmd.Parameters.Add(new SqlParameter("spouseDOB", this.SpouseDOB.HasValue? (object)this.SpouseDOB.Value : DBNull.Value));
            cmd.Parameters.Add(new SqlParameter("userType", this.UserType));
            cmd.Parameters.Add(new SqlParameter("password", this.UserPassword));
            cmd.Parameters.Add(new SqlParameter("userID", this.UserID));

            int rowCount = 0;
            try
            {
                rowCount = cmd.ExecuteNonQuery();
            }
            catch (Exception ex)
            { }
            if (rowCount > 0)
                return true;

            return false;
        }
    }
}