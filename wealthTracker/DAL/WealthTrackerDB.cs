using wealthTracker.Models;
using System;
using System.Collections.Generic;
using System.Data.Entity;
using System.Linq;
using System.Web;
using Microsoft.AspNet.Identity.EntityFramework;
using System.Data.Common;
using System.Reflection;

namespace wealthTracker.DAL
{
    public class WealthTrackerIdentityDB : IdentityDbContext<IdentityUser>
    {
        protected override void OnModelCreating(DbModelBuilder modelBuilder)
        {
            base.OnModelCreating(modelBuilder);
            modelBuilder.Entity<AppUser>().ToTable("Users");//table mapping
            modelBuilder.Entity<AssetAllocationMaster>().ToTable("AssetAllocationMaster");
        }

        public WealthTrackerIdentityDB()
            : base("WealthTrackerIdentityDB")
        {

        }

        public DbSet<AppUser> AppUsers { get; set; }
        public DbSet<AssetAllocationMaster> AssetAllocationMasters { get; set; }
        public DbSet<UserDetail> UserDetail { get; set; }

        public System.Data.Entity.DbSet<wealthTracker.Models.ClientFunding> ClientFundings { get; set; }

        //public DbSet<User> Users { get; set; }
    }

    public static class DataReaderExtensions
    {
        public static List<T> MapToList<T>(this DbDataReader dr) where T : new()
        {
            if (dr != null && dr.HasRows)
            {
                var entity = typeof(T);
                var entities = new List<T>();
                var propDict = new Dictionary<string, PropertyInfo>();
                var props = entity.GetProperties(BindingFlags.Instance | BindingFlags.Public);
                propDict = props.ToDictionary(p => p.Name.ToUpper(), p => p);

                while (dr.Read())
                {
                    T newObject = new T();
                    for (int index = 0; index < dr.FieldCount; index++)
                    {
                        if (propDict.ContainsKey(dr.GetName(index).ToUpper()))
                        {
                            var info = propDict[dr.GetName(index).ToUpper()];
                            if ((info != null) && info.CanWrite)
                            {
                                var val = dr.GetValue(index);
                                info.SetValue(newObject, (val == DBNull.Value) ? null : val, null);
                            }
                        }
                    }
                    entities.Add(newObject);
                }
                return entities;
            }
            return null;
        }
    }

    //public class WealthTrackerDB : DbContext
    //{
    //    protected override void OnModelCreating(DbModelBuilder modelBuilder)
    //    {
    //        base.OnModelCreating(modelBuilder);
    //        modelBuilder.Entity<User>().ToTable("Users");//table mapping

    //    }

    //    public DbSet<User> Users { get; set; }

    //    public DbSet<UserDetail> UserDetail { get; set; }
    //}
}