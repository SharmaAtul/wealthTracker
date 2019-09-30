using Microsoft.AspNet.Identity.EntityFramework;
using Microsoft.Owin.Security;
using Microsoft.Owin.Security.OAuth;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Security.Claims;
using System.Threading.Tasks;
using System.Web;
using wealthTracker.Models;

namespace wealthTracker.Providers
{
    public class SimpleAuthorizationServerProvider : OAuthAuthorizationServerProvider
    {
        public override async Task ValidateClientAuthentication(OAuthValidateClientAuthenticationContext context)
        {
            context.Validated();
        }

        public override async Task GrantResourceOwnerCredentials(OAuthGrantResourceOwnerCredentialsContext context)
        {
            context.OwinContext.Response.Headers.Add("Access-Control-Allow-Origin", new[] { "*" });
            AppUser user = null;
            using (AuthRepository _repo = new AuthRepository())
            {
                user = await _repo.FindUser(context.UserName, context.Password);

                if(user == null)
                {
                    context.SetError("invalid_grant", "The user name or password is incorrect.");
                    return;
                }
                else if(!user.IsActive)
                {
                    context.SetError("invalid_grant", "The user name or password is incorrect.");
                    return;
                }
            }

            var identity = new ClaimsIdentity(context.Options.AuthenticationType);
            identity.AddClaim(new Claim("sub", context.UserName));
            identity.AddClaim(new Claim(ClaimTypes.SerialNumber, user.UserID.ToString()));
            identity.AddClaim(new Claim(ClaimTypes.Role, user.UserType));
            identity.AddClaim(new Claim("ClientId", user.ClientId.ToString()));

            //AppUser user = AppUser.GetByEmail(context.UserName);

            var props = new AuthenticationProperties(new Dictionary<string, string>
                {
                    {
                        "user_role", user.UserType
                    },
                    {
                        "user_id", user.UserID.ToString()
                    }
                });

            var ticket = new AuthenticationTicket(identity, props);
            context.Options.AccessTokenExpireTimeSpan = TimeSpan.FromDays(1);
            //var authenticationManager = context;
            //authenticationManager.SignIn(id);

            context.Validated(ticket);

        }
        public override Task TokenEndpoint(OAuthTokenEndpointContext context)
        {
            foreach (KeyValuePair<string, string> property in context.Properties.Dictionary)
            {
                if(property.Key == "user_role" || property.Key == "user_id")
                    context.AdditionalResponseParameters.Add(property.Key, property.Value);
            }

            return Task.FromResult<object>(null);
        }
        public override Task GrantRefreshToken(OAuthGrantRefreshTokenContext context)
        {
            //validate your client  
            //var currentClient = context.ClientId;  

            //if (Client does not match)  
            //{  
            //    context.SetError("invalid_clientId", "Refresh token is issued to a different clientId.");  
            //    return Task.FromResult<object>(null);  
            //}  

            // Change authentication ticket for refresh token requests  
            var newIdentity = new ClaimsIdentity(context.Ticket.Identity);
            newIdentity.AddClaim(new Claim("newClaim", "newValue"));

            var newTicket = new AuthenticationTicket(newIdentity, context.Ticket.Properties);
            context.Validated(newTicket);

            return Task.FromResult<object>(null);
        }
    }
}